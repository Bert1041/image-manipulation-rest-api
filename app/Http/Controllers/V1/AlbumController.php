<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAlbumRequest;
use App\Http\Requests\UpdateAlbumRequest;
use App\Http\Resources\V1\AlbumResource;
use App\Models\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return AlbumResource::collection(Album::where('user_id', $request->user()->id)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAlbumRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAlbumRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = $request->user()->id;
        return new AlbumResource(Album::create($data));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Album $album)
    {
        // $data = $request->all();
        // $data['user_id'] = $request->user()->id;
        // return new AlbumResource(Album::create($data));
        if ($album->user_id != $request->user()->id) {
            return abort(403, 'Unauthorized action.');
        }
        return new AlbumResource($album);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAlbumRequest  $request
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAlbumRequest $request, Album $album)
    {
        if ($album->user_id != $request->user()->id) {
            return abort(403, 'Unauthorized action.');
        }
        $album->update($request->all());
        return new AlbumResource($album);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Album $album)
    {
        if ($album->user_id != $request->user()->id) {
            return abort(403, 'Unauthorized action.');
        }
        $album->delete();
        return response('', 204);
    }
}
