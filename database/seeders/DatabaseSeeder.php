<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use App\Models\BarangayPurok;
use App\Models\Consumer;
use App\Models\User;


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
        # Populate Barangay - Purok Data
        $this->createBrgyPurokData();

        # Populate Users and Consumers Data
        $this->createConsumersData();

        # Populate Readings Data
        //$this->createReadingsData();
    }

    public function createBrgyPurokData()
    {
        $csv_file = __DIR__ . '/brgy_purok.csv';
        $data = $this->getCSVFileData($csv_file);
        print "Creating 'barangay-purok' data.\n\n";
        foreach($data as $row) {
            $saved = BarangayPurok::create($row);
            print "\t{$row['barangay']} - {$row['purok']} successfully saved!!!\n";
        }
    }

    public function createConsumersData()
    {
        $csv_file = __DIR__ . '/consumers.csv';
        $data = $this->getCSVFileData($csv_file);
        print "\n\nCreating 'consumers - users' data.\n";
        foreach($data as $row) {
            if ($row['email'] == '' || $row['first_name'] == '' || $row['last_name'] == '') continue;
            # Populate users table
            $row['email'] = preg_replace('/\s+/', '', strtolower($row['email']));
            $fields = app(User::class)->getFillable();
            $user = array();
            foreach ($fields as $field) {
                $user[$field] = $row[$field];
            }
            $user['password'] = Hash::make($user['password']);
            $saved = User::create($user);
            if ($saved) {
                print "\n\tUser {$row['email']} successfully saved!!!";
                # Populate consumers table
                $row['user_key'] = User::getUserKey($user['email']);
                $row['brgyprk_id'] = BarangayPurok::getBrgyPrkId($row['barangay'], $row['purok']);
                $row['birthday'] = strtotime($row['birthday']);
                $fields = app(Consumer::class)->getFillable();
                $consumer = array();
                foreach ($fields as $field) {
                    $consumer[$field] = $row[$field];
                }
                $saved = Consumer::create($consumer);
                if ($saved) {
                    print "\n\tConsumer {$row['last_name']}, {$row['first_name']} successfully saved!!!\n";
                }
            }
        }
    }

    public function createReadingsData()
    {
        $csv_file = __DIR__ . '/consumers.csv';
        $data = $this->getCSVFileData($csv_file);
        print "\n\nCreating 'consumers - users' data.\n";
        foreach($data as $row) {
            if ($row['email'] == '' || $row['first_name'] || $row['last_name']) continue;
            # Populate users table
            $fields = app(User::class)->getFillable();
            $user = array();
            foreach ($fields as $field) {
                $user[$field] = $row[$field];
            }
            $user['password'] = Hash::make($user['password']);
            $saved = User::create($user);
            if ($saved) {
                print "\n\tUser {$row['email']} successfully saved!!!";
                # Populate consumers table
                $row['user_key'] = User::getUserKey($user['email']);
                $row['brgyprk_id'] = BarangayPurok::getBrgyPrkId($row['barangay'], $row['purok']);
                $row['birthday'] = strtotime($row['birthday']);
                $fields = app(Consumer::class)->getFillable();
                $consumer = array();
                foreach ($fields as $field) {
                    $consumer[$field] = $row[$field];
                }
                $saved = Consumer::create($consumer);
                if ($saved) {
                    print "\n\tConsumer {$row['last_name']}, {$row['first_name']} successfully saved!!!\n";
                }
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
