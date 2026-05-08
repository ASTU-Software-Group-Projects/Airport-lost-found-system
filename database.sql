-- =============================================
-- DATABASE: lost_found_db
-- =============================================

CREATE DATABASE IF NOT EXISTS lost_found_db;
USE lost_found_db;

-- =============================================
-- TABLE 1: lost_items
-- =============================================
CREATE TABLE IF NOT EXISTS lost_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    claim_code VARCHAR(20) UNIQUE NOT NULL,
    passenger_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    item_description TEXT,
    item_color VARCHAR(50),
    brand VARCHAR(100),
    lost_location VARCHAR(200),
    lost_date DATE,
    photo_path VARCHAR(500),
    status ENUM('pending', 'matched', 'returned') DEFAULT 'pending',
    reported_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =============================================
-- TABLE 2: found_items
-- =============================================
CREATE TABLE IF NOT EXISTS found_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    found_code VARCHAR(20) UNIQUE NOT NULL,
    staff_name VARCHAR(100) NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    item_description TEXT,
    item_color VARCHAR(50),
    brand VARCHAR(100),
    found_location VARCHAR(200),
    found_date DATE,
    storage_location VARCHAR(200),
    photo_path VARCHAR(500),
    matched_to INT DEFAULT NULL,
    status ENUM('unclaimed', 'matched', 'returned') DEFAULT 'unclaimed',
    found_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (matched_to) REFERENCES lost_items(id) ON DELETE SET NULL
);

-- =============================================
-- TABLE 3: staff_users
-- =============================================
CREATE TABLE IF NOT EXISTS staff_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role ENUM('staff', 'admin') DEFAULT 'staff',
    last_login DATETIME,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =============================================
-- DEFAULT STAFF ACCOUNT
-- =============================================
INSERT INTO staff_users (username, password, full_name, email, role) 
VALUES ('staff', MD5('staff123'), 'Airport Staff', 'staff@airport.com', 'staff')
ON DUPLICATE KEY UPDATE username=username;

-- =============================================
-- SAMPLE LOST ITEMS (10 records)
-- =============================================
INSERT INTO lost_items (claim_code, passenger_name, email, phone, item_name, item_description, item_color, lost_location, lost_date, status) VALUES
('LOST-A1B2C3', 'John Doe', 'john@example.com', '9876543210', 'Black Backpack', 'Nike backpack with Dell laptop inside', 'Black', 'Terminal 1 Gate A', CURDATE(), 'pending'),
('LOST-D4E5F6', 'Jane Smith', 'jane@example.com', '9876543211', 'iPhone 14', 'Silver iPhone with black case', 'Silver', 'Food Court', CURDATE(), 'pending'),
('LOST-G7H8I9', 'Mike Johnson', 'mike@example.com', '9876543212', 'Passport', 'US passport with blue cover', 'Blue', 'Security Check', CURDATE(), 'matched'),
('LOST-J0K1L2', 'Sarah Williams', 'sarah@example.com', '9876543213', 'Wallet', 'Brown leather wallet', 'Brown', 'Baggage Claim', DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'pending'),
('LOST-M3N4O5', 'David Brown', 'david@example.com', '9876543214', 'Sunglasses', 'Ray-Ban wayfarer', 'Black', 'Terminal 2 Gate C', DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'returned'),
('LOST-P6Q7R8', 'Emily Davis', 'emily@example.com', '9876543215', 'Laptop', 'MacBook Pro 14"', 'Silver', 'Terminal 1 Gate B', CURDATE(), 'pending'),
('LOST-S9T0U1', 'Robert Wilson', 'robert@example.com', '9876543216', 'Camera', 'Canon EOS DSLR', 'Black', 'Food Court', DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'pending'),
('LOST-V2W3X4', 'Lisa Anderson', 'lisa@example.com', '9876543217', 'Jacket', 'North Face black jacket', 'Black', 'Security Check', CURDATE(), 'pending'),
('LOST-Y5Z6A7', 'Tom Martinez', 'tom@example.com', '9876543218', 'Tablet', 'iPad Pro with pencil', 'Space Gray', 'Gate A waiting area', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'matched'),
('LOST-B8C9D0', 'Anna Taylor', 'anna@example.com', '9876543219', 'Keys', 'Car keys with remote', 'Black', 'Parking Area', CURDATE(), 'pending');

-- =============================================
-- SAMPLE FOUND ITEMS (10 records)
-- =============================================
INSERT INTO found_items (found_code, staff_name, item_name, item_description, item_color, found_location, found_date, storage_location, status) VALUES
('FND-1A2B3C', 'Airport Staff', 'Black Backpack', 'Nike backpack', 'Black', 'Gate A seating area', CURDATE(), 'Rack 5, Shelf B', 'unclaimed'),
('FND-4D5E6F', 'Airport Staff', 'iPhone 14', 'Silver iPhone', 'Silver', 'Food Court Table 3', CURDATE(), 'Counter 2, Drawer A', 'unclaimed'),
('FND-7G8H9I', 'Airport Staff', 'Passport', 'US passport', 'Blue', 'Security Check counter', CURDATE(), 'Safe Box 1', 'matched'),
('FND-0J1K2L', 'Airport Staff', 'Wallet', 'Brown leather wallet', 'Brown', 'Baggage Claim Carousel 4', DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'Counter 1, Drawer B', 'unclaimed'),
('FND-3M4N5O', 'Airport Staff', 'Sunglasses', 'Ray-Ban sunglasses', 'Black', 'Gate C waiting area', DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'Rack 2, Shelf A', 'returned'),
('FND-6P7Q8R', 'Airport Staff', 'Laptop', 'MacBook Pro', 'Silver', 'Terminal 1 Gate B', CURDATE(), 'Rack 3, Shelf C', 'unclaimed'),
('FND-9S0T1U', 'Airport Staff', 'Camera', 'Canon DSLR', 'Black', 'Food Court', DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Safe Box 2', 'unclaimed'),
('FND-2V3W4X', 'Airport Staff', 'Jacket', 'North Face jacket', 'Black', 'Security Check', CURDATE(), 'Rack 1, Shelf D', 'unclaimed'),
('FND-5Y6Z7A', 'Airport Staff', 'Tablet', 'iPad Pro', 'Space Gray', 'Gate A waiting area', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Rack 4, Shelf A', 'matched'),
('FND-8B9C0D', 'Airport Staff', 'Keys', 'Car keys', 'Black', 'Parking Area', CURDATE(), 'Counter 3, Drawer C', 'unclaimed');

-- Update matches
UPDATE found_items SET matched_to = (SELECT id FROM lost_items WHERE claim_code = 'LOST-G7H8I9') WHERE found_code = 'FND-7G8H9I';
UPDATE lost_items SET status = 'matched' WHERE claim_code = 'LOST-G7H8I9';
UPDATE found_items SET matched_to = (SELECT id FROM lost_items WHERE claim_code = 'LOST-Y5Z6A7') WHERE found_code = 'FND-5Y6Z7A';
UPDATE lost_items SET status = 'matched' WHERE claim_code = 'LOST-Y5Z6A7';
UPDATE found_items SET status = 'returned' WHERE found_code = 'FND-3M4N5O';
UPDATE lost_items SET status = 'returned' WHERE claim_code = 'LOST-M3N4O5';

SELECT '✅ Database setup complete!' AS Status;
