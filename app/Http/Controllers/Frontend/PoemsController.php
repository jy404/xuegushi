<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers\Frontend;

use DB;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class PoemsController extends Controller
{
    protected $query = null;
    /**
     * Create a new controller instance.
     *
     * @return mixed
     */
    public function __construct()
    {
        $this->query = 'poems';
    }

    /**
     * poems index page
     * @return mixed
     */
    public function index()
    {
        $poems = DB::table('poem')
            ->orderBy('like_count','desc')
            ->paginate(10);
        return view('frontend.poem.index')
            ->with('query','poems')
            ->with('site_title','诗文')
            ->with('poems',$poems);
    }
    /**
     * poem 详情页
     * @param $id
     * @return mixed
     */
    public function show($id){
        $author = null;
        $poem = DB::table('poem')->where('id',$id)->first();
        if($poem){
            $poem_detail = DB::table('poem_detail')->where('poem_id',$id)->first();
            if($poem->author != '佚名'){
                $author = DB::table('author')->where('author_name',$poem->author)->first();
            }
            return view('frontend.poem.show')
                ->with('query','poems')
                ->with('author',$author)
                ->with('detail',$poem_detail)
                ->with('site_title',$poem->title)
                ->with('poem',$poem);
        }else{
            return view('errors.404');
        }
    }
    /**
     * update poem database
     */
    public function updatePoemLikeCount(){
        $poems = DB::table('poem_like')->get();
        foreach ($poems as $key=>$poem){
            $res = DB::table('poem')
                ->where('title',$poem->title)
                ->where('author',$poem->author)
                ->where('dynasty',$poem->dynasty)
                ->update([
                    'type'=>$poem->type,
                    'like_count' =>$poem->like_count
                ]);
            print($key.'-----'.$poem->title.'<br>');
            if(!$res){
                break;
            }
            if($key+1 == count($poems)){
                print('ok!');
            }
        }
    }

}