<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\StudentPractic;
use App\Models\Teacher;
use App\Models\Stream;
use App\Models\Group;
use App\Models\Student;
use App\Models\Faculty;
use App\Models\Template;
use App\Models\Profile;

class DirectorateRequest extends Controller
{
    public static function get(Request $request)
    {
        $formEducation = ["Bakalavr" => "Бакалавриат", "Magis" => "Магистратура", "Zaoch" => "Заочное обучение"];
        $faculty = Faculty::all();
        $inst = Select_instituts();
        $profiles = Profile::all();

        return view('directorate', [
            'formRus' => "",
            'formEducation' => $formEducation,
            'faculty_id' => $faculty,
            'inst' => $inst,
            'profiles' => $profiles
        ]);
    }

    public static function post(Request $request)
    {
        if ($request->has('read_exel')) {
            create_excel($request->input('download'));
            //Download_Templace($request->input('download'));
        }

        if ($request->has('download')) {
            create_excel($request->input('download'));
            //Download_Templace($request->input('download'));
        }

        if ($request->has('done')) {
            Template::where('id', $request->input('done'))->update(['decanat_check' => 1, 'comment' => '']);
            //Download_Templace($request->input('done'));
        } elseif ($request->has('noShow')) {
            Template::where('id', $request->input('noShow'))->update(['decanat_check' => 0, 'comment' => '']);
            //Download_Templace($request->input('done'));
        } elseif ($request->has('remake')) {
            Template::where('id', $request->input('remake'))->update(['decanat_check' => 2, 'comment' => $request->input('comment', '')]);
            //Download_Templace($request->input('remake'));
        }
        return redirect('/directorate');
    }
}

/////////////
function Download_Templace($name)
{
    $file = $name;// "../../direktsiya/".$name;
    $filename = str_replace(['../../direktsiya/uploads/'], "", $name);
    if (!file_exists($file)) {
        die ('file not found');

    } else {
        ob_end_clean();
        header("Content-Description: File Transfer");
        header("Content-Type: text/Xls");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Content-Transfer-Encoding: binary");
        #header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header("Content-Length: " . filesize($file));

        readfile($file);
    }
}

function getAll()
{
    // Получаем список таблиц из базы данных Practices
    $tables = DB::select('SHOW TABLES FROM Practices');

    // Итерируем по результатам запроса и выводим информацию о каждой таблице
    foreach ($tables as $table) {
        print_r($table);
        echo "<br>";
    }

    // Получаем все данные из таблицы templates
    $templates = Template::get();

    // Итерируем по результатам запроса и выводим информацию о каждой записи
    foreach ($templates as $template) {
        print_r((array) $template);
        echo "<br>";
    }
}

function Select_instituts()
{
    $resultset = Faculty::where('id', 96)->get();
    return $resultset;
}

function Select_profiles($id_inst)
{
    $resultset = Profile::where('faculty_id', $id_inst)->get();
    return $resultset;
}

function Select_streams_b($id_prof)
{
    $year = date("Y") - 4;
    if (date("m") > 9) {
        $year++;
    }

    $resultset = Stream::where('profile_id', $id_prof)
        ->where('profile_id', 'NOT LIKE', '1')
        ->where('name', 'REGEXP', '.б-')
        ->where('year', '>=', $year)
        ->whereHas('groups', function ($query) {
            $query->whereRaw('(select count(*) from groups where stream_id = streams.id) > 0');
        })
        ->orderBy('name')
        ->get();

    return $resultset;
}

function Select_streams_m($id_prof)
{
    $year = date("Y") - 2;
    if (date("m") > 9) {
        $year++;
    }

    $resultset = Stream::where('profile_id', $id_prof)
        ->where('profile_id', 'NOT LIKE', '1')
        ->where('name', 'REGEXP', '.м-')
        ->where('year', '>=', $year)
        ->whereHas('groups', function ($query) {
            $query->whereRaw('(select count(*) from groups where stream_id = streams.id) > 0');
        })
        ->orderBy('name')
        ->get();

    return $resultset;
}

function Select_streams_z($id_prof)
{
    $year = date("Y") - 5;
    if (date("m") > 9) {
        $year++;
    }

    $resultset = Stream::where('profile_id', $id_prof)
        ->where('profile_id', 'NOT LIKE', '1')
        ->where('name', 'REGEXP', '.з-')
        ->where('year', '>=', $year)
        ->whereHas('groups', function ($query) {
            $query->whereRaw('(select count(*) from groups where stream_id = streams.id) > 0');
        })
        ->orderBy('name')
        ->get();

    return $resultset;
}

function Select_group($id_stream)
{
    $resultset = Group::where('stream_id', $id_stream)->get();
    return $resultset;
}

function Select_templates($id_group)
{
    $resultset = Template::where('group_id', $id_group)->get();
    return $resultset;
}

function Select_student($id_group)
{
    $resultset = Student::where('group_id', $id_group)->get();
    return $resultset;
}

function Select_student_practic($id_student)
{
    $student_practic = StudentPractic::where('student_id', $id_student)->where('status', 1)->first();
    return $student_practic;
}

function Select_teacher($id_teacher)
{
    $teacher = Teacher::where('id', $id_teacher)->first();
    return $teacher;
}