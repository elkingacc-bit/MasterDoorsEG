-- Budget vs Actual + Fixed Assets Register
-- Run once against the target DB (local XAMPP and, separately, the production host).

CREATE TABLE IF NOT EXISTS `budgetPlan` (
  `budgetId` INT AUTO_INCREMENT PRIMARY KEY,
  `accountCode` VARCHAR(20) NOT NULL,
  `budgetYear` INT NOT NULL,
  `budgetMonth` TINYINT NOT NULL,
  `budgetAmount` DECIMAL(15,2) NOT NULL,
  `createdBy` VARCHAR(100),
  `createdDate` DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uniq_account_period` (`accountCode`,`budgetYear`,`budgetMonth`)
);

CREATE TABLE IF NOT EXISTS `fixedAssets` (
  `fixedAssetId` INT AUTO_INCREMENT PRIMARY KEY,
  `assetName` VARCHAR(255) NOT NULL,
  `assetCategory` VARCHAR(100),
  `accountCode` VARCHAR(20) DEFAULT NULL,
  `purchaseDate` DATE NOT NULL,
  `purchaseCost` DECIMAL(15,2) NOT NULL,
  `usefulLifeYears` INT NOT NULL,
  `salvageValue` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `status` ENUM('Active','Disposed') NOT NULL DEFAULT 'Active',
  `disposalDate` DATE DEFAULT NULL,
  `createdBy` VARCHAR(100),
  `createdDate` DATETIME DEFAULT CURRENT_TIMESTAMP
);
