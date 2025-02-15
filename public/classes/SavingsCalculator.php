<?php
require_once 'Database.php';

class SavingsCalculator {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function calculateSystem($monthlyBill, $phase) {
        // Validate phase and bill amount
        if ($phase == 1 && $monthlyBill > 35000) {
            throw new Exception("Bills over 35,000 LKR cannot be supported on single phase.");
        }
        
        // Calculate units consumed based on bill amount
        $unitsConsumed = $this->calculateUnitsFromBill($monthlyBill);
        
        // Calculate recommended system size (kW)
        $recommendedSystem = ceil($unitsConsumed / 135); // 135 kWh per 1kW system per month
        
        // Calculate monthly generation (kWh)
        $monthlyGeneration = $recommendedSystem * 135;
        
        // Calculate system cost
        $estimatedCost = $this->calculateSystemCost($recommendedSystem);
        
        // Calculate monthly savings
        $monthlySavings = $monthlyBill - $this->calculateNewBill($unitsConsumed - $monthlyGeneration);
        
        // Store calculations in database
        $this->storeCalculation($monthlyBill, $recommendedSystem, $monthlySavings, $estimatedCost, $monthlyGeneration);
        
        return [
            'recommended_system' => $recommendedSystem,
            'monthly_generation' => $monthlyGeneration,
            'estimated_cost' => $estimatedCost,
            'units_consumed' => $unitsConsumed,
            'monthly_bill' => $monthlyBill,
            'monthly_savings' => $monthlySavings,
            'solar_monthly_installment' => $estimatedCost / 120 // Assuming 10-year payment plan
        ];
    }
    
    private function calculateUnitsFromBill($bill) {
        $bill = floatval($bill);
        
        if ($bill > 0) {
            if ($bill <= (60 * 7.85)) {
                return $bill / 7.85;
            } elseif ($bill <= (90 * 10.00 + 90)) {
                return ($bill - 90) / 10.00;
            } elseif ($bill <= (120 * 27.75 + 480)) {
                return ($bill - 480) / 27.75;
            } elseif ($bill <= (180 * 32.00 + 480)) {
                return ($bill - 480) / 32.00;
            } else {
                return ($bill - 2360) / 89.00;
            }
        }
        return 0;
    }
    
    private function calculateSystemCost($size) {
        $costPerKw = [
            2 => 450000,  // 2kW system
            3 => 650000,  // 3kW system
            5 => 1000000, // 5kW system
            10 => 1800000, // 10kW system
            20 => 3200000, // 20kW system
            40 => 6000000  // 40kW system
        ];
        
        // Find the closest system size
        $availableSizes = array_keys($costPerKw);
        $closestSize = $availableSizes[0];
        foreach ($availableSizes as $availableSize) {
            if (abs($size - $availableSize) < abs($size - $closestSize)) {
                $closestSize = $availableSize;
            }
        }
        
        return $costPerKw[$closestSize];
    }
    
    private function calculateNewBill($units) {
        if ($units <= 0) return 0;
        
        if ($units <= 60) {
            return $units * 7.85;
        } elseif ($units <= 90) {
            return ($units * 10.00) + 90;
        } elseif ($units <= 120) {
            return ($units * 27.75) + 480;
        } elseif ($units <= 180) {
            return ($units * 32.00) + 480;
        } else {
            return ($units * 89.00) + 2360;
        }
    }
    
    private function storeCalculation($monthlyBill, $suggestedSystem, $monthlySavings, $estimatedCost, $unitGeneration) {
        $sql = "INSERT INTO calculated_data (monthly_bill, suggested_system, monthly_savings, estimated_cost, unit_genaration) 
                VALUES (?, ?, ?, ?, ?)";
        
        // Create temporary variables for binding
        $system = $suggestedSystem . 'kW';
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("dsddd", 
            $monthlyBill,
            $system,
            $monthlySavings,
            $estimatedCost,
            $unitGeneration
        );
        
        $stmt->execute();
        $stmt->close();
    }
}