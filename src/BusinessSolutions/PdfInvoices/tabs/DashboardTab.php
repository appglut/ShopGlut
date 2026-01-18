<?php
namespace Shopglut\BusinessSolutions\PdfInvoices\Tabs;

class DashboardTab {

    public function render() {
        // Use the new professional dashboard
        require_once dirname( __DIR__ ) . '/DashboardPage.php';
        $dashboard = new \ShopGlutPdfInvoicesDashboard();
        $dashboard->render_dashboard();
    }
}