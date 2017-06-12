<?php

namespace App\Http\Controllers;

use App\News;
use App\Report;
use Illuminate\Http\Request;
use Validator;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Report::get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required_if:type,normal',
            'lat' => 'required',
            'lon' => 'required',
            'photo' => 'required_if:type,normal',
            'type' => 'required',
            'location' => 'required',
            'area' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $files = $request->file('photo');

        $input = $request->all();
        $input['user_id'] = \Auth::user()->id;
        $report = Report::create($input);
        foreach ($files as $file) {
            $fileName = "";
            if (!empty($file)) {
                $fileName = time() . $file->getClientOriginalName();
                $format = time() . $file->getClientMimeType();
                $file->move('reports', $fileName);
            }

            $report->attachment()->create([
                'report_id' => $report->id,
                'file' => $fileName,
                'format' => $format
            ]);
        }
        return response()->json(['msg' => 'Laporan anda telah diterima']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Report::where('user_id', $id)->with('attachment')->paginate(10);
        if ( count($data) == 0 ) {
            return response()->json(['msg' => 'Tidak ada data']);
        }
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function near(News $report, Request $request)
    {
        $latitude = $request->lat;
        $longitude = $request->lon;

        return response()->json($report->nearest($latitude, $longitude)->paginate(10));
    }
}
