<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $testimonials = Testimonial::latest()->get();
        return view('home.testimonial.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('home.testimonial.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|numeric',
            'review' => 'required',
        ]);

        $image = $request->image;

        if ($request->image) {
            $image = $request->image->getClientOriginalName();
            $request->image->move(public_path('assets/img/testimonials'), $image);
        }

        $testimonial = new Testimonial();
        $testimonial->name = $request->name;
        $testimonial->occupation = $request->occupation;
        $testimonial->title = $request->title;
        $testimonial->rating = $request->rating;
        $testimonial->review = $request->review;
        $testimonial->rated_at = $request->rated_at;
        $testimonial->rated_url = $request->rated_url;
        $testimonial->image = $image ?? "testimonial-placeholder.jpg";
        $testimonial->save();
        return redirect('/testimonials')->with('message', 'Testimonial updated successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Testimonial  $testimonial
     * @return \Illuminate\Http\Response
     */
    public function show(Testimonial $testimonial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Testimonial  $testimonial
     * @return \Illuminate\Http\Response
     */
    public function edit(Testimonial $testimonial)
    {
        return view('home.testimonial.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Testimonial  $testimonial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'rating' => 'required|numeric',
            'review' => 'required',
        ]);

        $image = $request->image;

        if ($request->image) {
            $image = $request->image->getClientOriginalName();
            $request->image->move(public_path('assets/img/testimonials'), $image);
            $old_image = public_path('assets/img/testimonials/' . $testimonial->image);
            if (File::exists($old_image)) {
                unlink($old_image);
            }
        }

        $testimonial->name = $request->name;
        $testimonial->occupation = $request->occupation;
        $testimonial->title = $request->title;
        $testimonial->rating = $request->rating;
        $testimonial->review = $request->review;
        $testimonial->rated_at = $request->rated_at;
        $testimonial->rated_url = $request->rated_url;
        if ($image) {
            $testimonial->image = $image;
        }
        $testimonial->save();
        return back()->with('message', 'Testimonial addded successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Testimonial  $testimonial
     * @return \Illuminate\Http\Response
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect('/testimonials')->with('message', 'Testimonial deleted successfully');
    }
}
