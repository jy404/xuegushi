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

class SearchController extends Controller{

    /**
     * 搜索功能
     * 暂时只支持诗人名字以及古诗名搜索
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request){
        $_key = $request->input('value');
        $message = null;
        $authors = DB::table('dev_author')
            ->where('author_name','like','%'.$_key.'%')
            ->select('id','dynasty','author_name','like_count')
            ->orderBy('like_count','desc')
            ->limit(5)
            ->get();
        $tags = DB::table('dev_poem')
            ->where('tags','like','%'.$_key.'%')
            ->select('tags')
            ->limit(5)
            ->get();
        $_tags = array();
        foreach ($tags as $tag){
            foreach(explode(',',$tag->tags) as $_tag){
                if(strpos($_tag,$_key)!== false){
                    array_push($_tags,$_tag);
                }
            }
        }
        $_tags = array_unique($_tags);
        $poems = DB::table('dev_poem')
            ->where('title','like','%'.$_key.'%')
            ->select('id','title','author','dynasty','like_count')
            ->orderBy('like_count','desc')
            ->limit(5)
            ->get();
        return view('frontend.partials.searchBox')
            ->with('keyword',$_key)
            ->with('authors',$authors)
            ->with('tags',$_tags)
            ->with('poems',$poems);
    }
}