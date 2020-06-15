<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\students;
use App\studentsScores;
use Validator;
use Exception;

use Illuminate\Http\Request;

class DatabasesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(request()->ajax())
        {
            return datatables()->of(students::latest()->get())
            ->addColumn('action', function($data){
                $button = '<button type="button" name="edit" id="'.$data->id.'" data-toggle="tooltip" title="Lihat Data" class="edit btn btn-warning btn-sm my-1 d-inline"><i class="fa fa-pencil-alt"></i></button>';
                $button .= '&nbsp;&nbsp;';
                $button .= '<button type="button" name="delete" id="'.$data->id.'" data-toggle="tooltip" title="Delete Data" class="delete btn btn-danger btn-sm my-1 d-inline"><i class="fa fa-trash-alt"></i></button>';
                // $button .= '&nbsp; &nbsp;';

                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
        };

        return view('pages.admin.database.indexDaftarSiswa');
    }

    public function indexNilaiDetail()
    {
        if(request()->ajax())
        {
            return datatables()->of(studentsScores::with('nisStudents')->latest()->get())
            ->addColumn('nis_student', function($nis){
                return $nis->nisStudents->nis; 
            })
            ->addColumn('nama', function($nama){
                return $nama->nisStudents->nama;
            })
            ->addColumn('action', function($data){
                $button = '<button type="button" name="edit" id="'.$data->nis_student.'" data-toggle="tooltip" title="Edit Data" class="edit btn btn-warning btn-sm m-1 d-inline"><i class="fa fa-pencil-alt"></i></button>';
                // $button .= '&nbsp;&nbsp;';
                // $button .= '<button type="button" name="delete" id="'.$data->nis_student.'" data-toggle="tooltip" title="Delete Data" class="delete btn btn-danger btn-sm m-1 d-inline"><i class="fa fa-trash-alt"></i></button>';
                // $button .= 'sdjfoiosdfjsdhfioeh';
                return $button;
            })
            ->rawColumns(['nis_student','nama','action'])
            ->make(true);
        };

        return view('pages.admin.database.indexDetailNilai');
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
        $errormsg = "";

        $rules = array(
            'nis' => 'required',
            'nama' => 'required',
            'kelas' => 'required',
        );
        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $data = $request->all();
        // $nis_student = $request->nis;
        // studentsScores::create(['nis_student' => $request->input('nis')]);

        try
        {
            students::create($data); 
            // studentsScores::create(['nis_student' => $request->input('nis')]);
            return response()->json(['success' => 'Data Added successfully.']);
        }catch(Exception $exception){
            $errormsg = 'Database error! ' . $exception->getCode();
            return response()->json(['errors' => 'Error Input ' . '<br>' . $errormsg]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editDataSiswa($id)
    {
        if(request()->ajax())
        {
            $data = students::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    public function editNilai($id)
    {
        if(request()->ajax())
        {
            $data = studentsScores::with('nisStudents')->findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateDataSiswa(Request $request)
    {
        $errormsg = "";

        $rules = array(
            'nis' => 'required',
            'nama' => 'required',
            'kelas' => 'required',
        );
        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        try
        {
            $data = $request->all();
            $dataSiswa = students::findOrFail($request->hidden_id);
            $dataSiswa -> update($data);
            // studentsScores::create(['nis_student' => $request->input('nis')]);
            return response()->json(['success' => 'Data Added successfully.']);
        }catch(Exception $exception){
            $errormsg = 'Database error! ' . $exception->getMessage();
            return response()->json(['errors' => 'Error Input ' . '<br>' . $errormsg]);
        }
    }

    public function updateNilaiDetail(Request $request)
    {
        $errormsg = "";

        $rules = array(
            'matematika' => 'required',
            'fisika' => 'required',
            'kimia' => 'required',
            'biologi' => 'required',
            'sejarah' => 'required',
            'geografi' => 'required',
        );
        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        // $data = $request->except('_token','hidden_id');

        // $totalNilai = sum($request->all);
        // dd($totalNilai);
        // $data = $request->all();
        // dd($data);
        // $nis_student = $request->nis;
        // studentsScores::create(['nis_student' => $request->input('nis')]);

        try
        {
            $data = $request->all();
            $data['totalNilai'] = $request->matematika + $request->fisika + $request->kimia + $request->biologi + $request->sejarah + $request->geografi;
            $data['nilaiAkhir'] = $data['totalNilai']/6;
            // dd($data);
            $nilai = studentsScores::findOrFail($request->hidden_id);
            $nilai -> update($data);
            // studentsScores::create(['nis_student' => $request->input('nis')]);
            return response()->json(['success' => 'Data Added successfully.']);
        }catch(Exception $exception){
            $errormsg = 'Database error! ' . $exception->getMessage();
            return response()->json(['errors' => 'Error Input ' . '<br>' . $errormsg]);
        }

        // return view('pages.admin.database.indexDetailNilai');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyDataSiswa($id)
    {
        $data = students::findOrFail($id);
        $data->delete();

        studentsScores::where('id', $id)->delete();
    }
}
