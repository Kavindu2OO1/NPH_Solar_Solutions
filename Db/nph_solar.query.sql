CREATE DATABASE nph_solar;
use nph_solar;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,  
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    id_number VARCHAR(12) UNIQUE,
    phone_number VARCHAR(10),
	phase_count ENUM('Single Phase', 'Three Phase') NOT NULL,
    user_type ENUM('Admin', 'User', 'Manager') NOT NULL,
    property_type ENUM('Residential', 'Commercial') NOT NULL,
    address TEXT,
    city VARCHAR(100),
    postal_code VARCHAR(5),
    password_hash TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ✅

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_url TEXT,
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ✅

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL,
    phase_count ENUM('Single Phase', 'Three Phase') NOT NULL,
    status ENUM('Pending', 'In Progress', 'Completed', 'Cancelled') DEFAULT 'Pending',
    price DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ✅

CREATE TABLE calculated_data (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    monthly_bill DECIMAL(10,2) NOT NULL,
    suggested_system VARCHAR(10) NOT NULL,
    monthly_savings  DECIMAL(10,2),
    estimated_cost DECIMAL(10,2),
    unit_genaration DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ✅

CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    id_number VARCHAR(12),
    phone_number VARCHAR(10),
    phase_count ENUM('Single Phase', 'Three Phase') NOT NULL,
    res_or_commer ENUM('Residential', 'Commercial') NOT NULL,
    address TEXT,
    preferred_date DATE NOT NULL,
    status ENUM('Pending', 'Confirmed', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ✅

CREATE TABLE shopping_cart (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    product_id INT REFERENCES products(id) ON DELETE CASCADE,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ✅


CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('Pending', 'Paid', 'Shipped', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ✅

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    order_id INT REFERENCES orders(id) ON DELETE CASCADE,
    product_id INT REFERENCES products(id) ON DELETE CASCADE,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL
);

-- ✅

CREATE TABLE login_history (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    action ENUM('Login', 'Logout') NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(50)
);

-- ✅


CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    message TEXT NOT NULL,
    type ENUM('Order', 'Project', 'Appointment') NOT NULL,
    status ENUM('Unread', 'Read') DEFAULT 'Unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ✅
CREATE TABLE `order_items` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `order_id` INT(11) NOT NULL,
    `product_id` INT(11) NOT NULL,
    `quantity` INT(11) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`),
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
) 
ENGINE=InnoDB 
DEFAULT CHARSET=utf8mb4;





