<?php

namespace App\Http\Controllers\Team;

use App\SMSHistory;
use App\SMSInbox;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Exceptions\LaravelExcelException;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;

class CommonDataController extends Controller
{

    //======================================================================
    // getCsvFileInfo Function Start Here
    //======================================================================
    public function getCsvFileInfo(Request $request)
    {

        try {

            $file_extension = $request->file('import_numbers')->getClientOriginalExtension();
            $supportedExt   = array('csv', 'xls', 'xlsx');

            if (isset($supportedExt) && is_array($supportedExt) && !in_array_r(strtolower($file_extension), $supportedExt)) {
                return response()->json(['status' => 'error', 'message' => language_data('Insert Valid Excel or CSV file')]);
            }

            $all_data = Excel::toArray([], $request->file('import_numbers'));
            $all_data = array_shift($all_data);

            if (isset($all_data) && is_array($all_data) && array_empty($all_data)) {
                return response()->json(['status' => 'error', 'message' => 'Empty Field']);
            }

            $counter = "A";
            if ($request->header_exist == 'true') {

                $header = array_shift($all_data);

                foreach ($header as $key => $value) {
                    if (!$value) {
                        $header[$key] = "Column " . $counter;
                    }

                    $counter++;
                }
            } else {

                $header_like = $all_data[0];

                $header = array();

                foreach ($header_like as $h) {
                    array_push($header, "Column " . $counter);
                    $counter++;
                }
            }

            $all_data = array_map(function ($row) use ($header) {

                return array_combine($header, $row);
            }, $all_data);


            return response()->json(["status" => "success", "data" => $all_data]);
        } catch (LaravelExcelException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    //======================================================================
    // getClientCsvFileInfo Function Start Here
    //======================================================================
    public function getClientCsvFileInfo(Request $request)
    {

        try {

            $file_extension = $request->file('import_client')->getClientOriginalExtension();
            $supportedExt   = array('csv', 'xls', 'xlsx');

            if (isset($supportedExt) && is_array($supportedExt) && !in_array_r(strtolower($file_extension), $supportedExt)) {
                return response()->json(['status' => 'error', 'message' => language_data('Insert Valid Excel or CSV file')]);
            }

            $all_data = Excel::toArray([], $request->import_client);
            $all_data = array_shift($all_data);
            if ($all_data && is_array($all_data) && array_empty($all_data)) {
                return response()->json(['status' => 'error', 'message' => 'Empty Field']);
            }

            $counter = "A";

            if ($request->header_exist == 'true') {

                $header = array_shift($all_data);

                foreach ($header as $key => $value) {
                    if (!$value) {
                        $header[$key] = "Column " . $counter;
                    }

                    $counter++;
                }
            } else {

                $header_like = $all_data[0];

                $header = array();

                foreach ($header_like as $h) {
                    array_push($header, "Column " . $counter);
                    $counter++;
                }
            }

            $all_data = array_map(function ($row) use ($header) {

                return array_combine($header, $row);
            }, $all_data);


            return response()->json(["status" => "success", "data" => $all_data]);
        } catch (LaravelExcelException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
