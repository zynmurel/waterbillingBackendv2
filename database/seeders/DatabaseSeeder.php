<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use App\Models\Settings;
use App\Models\BarangayPurok;
use App\Models\Consumer;
use App\Models\ServicePeriod;
use App\Models\Reading;
use App\Models\Billing;
use App\Models\Payment;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        # Populate settings, e.g cubic rates, penalty rate, due date
        $this->createSettingsData();

        # Populate Barangay - Purok Data
        $this->createBrgyPurokData();

        # Populate Users and Consumers Data
        $this->createConsumersData();

        # Populate Service Periods Data
        $this->createServicePeriodData();

        # Populate Readings Data
        $this->createReadingsData();

        # Populate Billings Data
        $this->createBillingsData();

        # Populate Payments Data
        $this->createPaymentsData();
    }

    public function createSettingsData()
    {
        $csv_file = __DIR__ . '/settings.csv';
        $data = $this->getCSVFileData($csv_file);
        $csv_file = __DIR__ . '/cubic_rates.csv';
        $cubic_rates = $this->getCSVFileData($csv_file);
        $data[] = array(
            'setting_type' => 'cubic_rates',
            'setting_value' => json_encode($cubic_rates),
        );
        print "Creating 'settings' data (including cubic rates).\n\n";
        foreach($data as $row) {
            $saved = Settings::create($row);
            if ($saved) {
                print "\t{$row['setting_type']} successfully saved!!!\n";
            }
        }
    }

    public function createBrgyPurokData()
    {
        $csv_file = __DIR__ . '/brgy_purok.csv';
        $data = $this->getCSVFileData($csv_file);
        print "Creating 'barangay-purok' data.\n\n";
        foreach($data as $row) {
            $saved = BarangayPurok::create($row);
            if ($saved) {
                print "\t{$row['barangay']} - {$row['purok']} successfully saved!!!\n";
            }
        }
    }

    public function createConsumersData()
    {
        $csv_file = __DIR__ . '/consumers.csv';
        $data = $this->getCSVFileData($csv_file);
        print "\n\nCreating 'consumers - users' data.\n";
        foreach($data as $row) {
            if ($row['email'] == '' || $row['first_name'] == '' || $row['last_name'] == '') continue;
            $success = Consumer::addNewConsumer($row);
            if ($success['user']) {
                print "\n\tUser {$row['email']} successfully saved!!!";
                if ($success['consumer']) {
                    print "\n\tConsumer {$row['last_name']}, {$row['first_name']} successfully saved!!!\n";
                }
            }
        }
    }

    public function createServicePeriodData()
    {
        $csv_file = __DIR__ . '/service_periods.csv';
        $data = $this->getCSVFileData($csv_file);
        print "Creating 'service periods' data.\n\n";
        foreach($data as $row) {
            $row['is_current'] = $row['is_current'] ? true : false;
            $saved = ServicePeriod::create($row);
            if ($saved) {
                print "\t{$row['service_period']} successfully saved!!!\n";
            }
        }
    }

    public function createReadingsData()
    {
        $csv_file = __DIR__ . '/readings.csv';
        $data = $this->getCSVFileData($csv_file);
        print "\n\nCreating 'readings' data.\n";
        foreach($data as $row) {
            $success = Reading::addNewReading($row);
            if ($success) {
                print "\tWater reading of {$row['consumer']} by {$row['reader']} successfully saved!!!\n";
            }
        }
    }

    public function createBillingsData()
    {
        $csv_file = __DIR__ . '/billings.csv';
        $data = $this->getCSVFileData($csv_file);
        print "\n\nCreating 'billings' data.\n";
        foreach($data as $row) {
            $success = Billing::addNewBilling($row);
            if ($success) {
                print "\tWater billing of {$row['consumer']} successfully saved!!!\n";
            }
        }
    }

    public function createPaymentsData()
    {
        $csv_file = __DIR__ . '/payments.csv';
        $data = $this->getCSVFileData($csv_file);
        print "\n\nCreating 'payments' data.\n";
        foreach($data as $row) {
            $success = Payment::addNewPayment($row);
            if ($success) {
                print "\tPayment of {$row['consumer']} successfully saved!!!\n";
            } 
        }
    }

    public function getCSVFileData($file) 
    {
        $data = array();
        if (is_file($file)) {
            $fd = fopen($file, "r");
            if ($fd == null) {
                die("Command 'fopen' failed for $file.");
            }
            $line = trim(fgets($fd));
            $headers = explode(',', $line);
            while (!feof($fd)) {
                $line = trim(fgets($fd));
                $token = explode(',', $line);
                $row = array();
                foreach ($headers as $i => $header) {
                    $row[$header] = $token[$i];
                }
                $data[] = $row;
            }
            fclose($fd);
        }

        return $data;
    }
}
