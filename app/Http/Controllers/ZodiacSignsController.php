<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\ZodiacSign;
use App\Http\Requests\ZodiacSignRequest;

class ZodiacSignsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $zodiacsigns= ZodiacSign::all();
        return view('zodiacsigns.index', ['zodiacsigns'=>$zodiacsigns]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('zodiacsigns.create');
    }

    /** 
     * Store a newly created resource in storage.
     *
     * @param  ZodiacSignRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ZodiacSignRequest $request)
    {
        $validatedData = $request->validate([
            'Aries' => 'required|image|max:2048',
            'Taurus' => 'required|image|max:2048',
            'Gemini' => 'required|image|max:2048',
            'Cancer' => 'required|image|max:2048',
            'leo' => 'required|image|max:2048',
            'Virgo' => 'required|image|max:2048',

            'Libra' => 'required|image|max:2048',
            'Scorpio' => 'required|image|max:2048',
            'Sagittarius' => 'required|image|max:2048',
            'Capricorn' => 'required|image|max:2048',
            'Aquarius' => 'required|image|max:2048',
            'Pisces' => 'required|image|max:2048',
        ]);
        dd($validatedData);
        $path = public_path('/images/zodiac');
        $images = [];

        $zodiacsign = new ZodiacSign;
        foreach ($validatedData as $key => $value) {
            $imagePath = $value->storeAs('zodiac', $key.'.jpg', 'public');
            array_push($images, $imagePath);

            // save the image path to the database
            $zodiac = new Zodiac;
            $zodiac->name = $key;
            $zodiac->image_path = $imagePath;
            $zodiac->save();
        }
		$zodiacsign->Aries = $request->input('Aries');
		$zodiacsign->Taurus = $request->input('Taurus');
		$zodiacsign->Gemini = $request->input('Gemini');
		$zodiacsign->Cancer = $request->input('Cancer');
		$zodiacsign->Leo = $request->input('Leo');
		$zodiacsign->Virgo = $request->input('Virgo');
		$zodiacsign->Libra = $request->input('Libra');
		$zodiacsign->Scorpio = $request->input('Scorpio');
		$zodiacsign->Sagittarius = $request->input('Sagittarius');
		$zodiacsign->Capricorn = $request->input('Capricorn');
		$zodiacsign->Aquarius = $request->input('Aquarius');
		$zodiacsign->Pisces = $request->input('Pisces');
        $zodiacsign->save();

        return to_route('zodiacsigns.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $zodiacsign = ZodiacSign::findOrFail($id);
        return view('zodiacsigns.show',['zodiacsign'=>$zodiacsign]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $zodiacsign = ZodiacSign::findOrFail($id);
        return view('zodiacsigns.edit',['zodiacsign'=>$zodiacsign]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ZodiacSignRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ZodiacSignRequest $request, $id)
    {
        $zodiacsign = ZodiacSign::findOrFail($id);
		$zodiacsign->Aries = $request->input('Aries');
		$zodiacsign->Taurus = $request->input('Taurus');
		$zodiacsign->Gemini = $request->input('Gemini');
		$zodiacsign->Cancer = $request->input('Cancer');
		$zodiacsign->Leo = $request->input('Leo');
		$zodiacsign->Virgo = $request->input('Virgo');
		$zodiacsign->Libra = $request->input('Libra');
		$zodiacsign->Scorpio = $request->input('Scorpio');
		$zodiacsign->Sagittarius = $request->input('Sagittarius');
		$zodiacsign->Capricorn = $request->input('Capricorn');
		$zodiacsign->Aquarius = $request->input('Aquarius');
		$zodiacsign->Pisces = $request->input('Pisces');
        $zodiacsign->save();

        return to_route('zodiacsigns.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $zodiacsign = ZodiacSign::findOrFail($id);
        $zodiacsign->delete();

        return to_route('zodiacsigns.index');
    }
}
