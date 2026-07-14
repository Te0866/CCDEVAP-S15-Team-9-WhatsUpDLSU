<?php
require_once __DIR__ . '/Database.php';

/**
 * OrganizationModel - DEPRECATED
 * Now that organizations table is removed, this model is no longer used.
 * All officer logic is handled through UserModel.
 */
class OrganizationModel {
    public function getTotalOrganizations() {
        // Return count of OFFICER role users instead
        $conn = Database::getConnection();
        $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE ROLE = 'OFFICER'");
        $row = mysqli_fetch_assoc($result);
        return (int) $row['total'];
    }

    // Stub methods to prevent fatal errors during transition
    public function getOrganizationById($orgId) { return null; }
    public function organizationNameExists($orgName, $excludeOrgId = null) { return false; }
    public function createOrganization($orgName) { return 0; }
    public function updateOrganization($orgId, $orgName) { return false; }
    public function deleteOrganization($orgId) { return false; }
}