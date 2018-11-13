<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Eloquent Models
use App\Event;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.events');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $categories = Category::all();

        // $backgrounds = ['bg1.jpg', 'bg2.jpg', 'bg3.jpg', 'bg4.jpg'];
        // $index = mt_rand(0,3);
        // $sample_image = $backgrounds[$index];

        // if(count($categories) > 0) {
        //     return view('user.create_article', compact('categories', 'sample_image'));
        // } else {
        //     return redirect()->route('articles')->with(['msg_class' => 'error', 'msg_fail' => 'It seems like there are no categories available.<br>Every article should be categorized, right? ;)<br><br>Standby while some categories magically appear.']);
        // }

        return view('user.create_event');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $rq)
    {
        try {

            $event_time = explode(" - ", $rq->event_time);
            $start_time = $event_time[0];
            $end_time = $event_time[1];

            $address = $rq->event_loca_address;
            $coordinates = $this->parseAddress($address);

            $latitude = "";
            $longitude = "";

            if ($coordinates != "")
            {
                $coordinates = explode(",", $coordinates);

                $latitude = preg_replace('/\s+/', '', $coordinates[0]);
                $longitude = preg_replace('/\s+/', '', $coordinates[1]);
            }

            $event = new Event;
            $event->user_id             = \Auth::user()->id;
            $event->event_title         = $rq->event_title;
            $event->event_description   = $rq->event_description;
            $event->event_loca_address  = $address;
            $event->event_lat           = $latitude;
            $event->event_long          = $longitude;
            $event->event_start_time    = date_create_from_format('d/m/Y H:i', $start_time);
            $event->event_end_time      = date_create_from_format('d/m/Y H:i', $end_time);
            $event->event_status        = 1;
            $event->event_max           = $rq->event_max;
            $event->event_organizer     = \Auth::user()->first_name.' '.\Auth::user()->last_name;

            $event->save();

            return redirect()->route('events')->with(['msg_class' => 'success', 'msg_success' => 'You have successfully created a meetup!']);
        } catch (Exception $e) {
            return back()->with(['msg_class' => 'error', 'msg_error' => 'Failed to create the meetup. Please try again.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $rq)
    {
        // $article = Article::find($rq->article_id);
        // $categories = Category::all();

        //return view('user.edit_article', compact('article', 'categories'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $event = Event::find($id);
            $event->delete();

            return back()->with(['msg_class' => 'success', 'msg_success' => 'Successfully removed event.']);
        } catch (Exception $e) {
            return back()->with(['msg_class' => 'error', 'msg_error' => 'Failed to remove event. Please try again.']);
        }
    }

    public function getEvents(Request $rq)
    {
        try
        {
            $creator = $rq->creator;
    
            $query = Event::query();

            if ($creator != null && $creator != "")
                $query = $query->where('user_id', $creator);

            $query = $query->orderBy('created_at', 'desc');

            $events = $query->get();

            $events = $events->toArray();

            return $events;
        }
        catch (Exception $e) 
        {
            return back()->with(['msg_class' => 'error', 'msg_error' => 'Failed to retrieve events.']);
        }
    }

    private function parseAddress($address)
    {
        $address = urlencode($address);
        $fullAddress = 'https://www.google.com/maps/place/'.$address;
        
        $fileContents = file_get_contents($fullAddress);
            
        $geocode = $this->get_string_between($fileContents, 'markers=', '&amp;sensor=');
        if ($geocode == "")
            $geocode = $this->get_string_between($fileContents, '&amp;ll=', '" itemprop="image"');

        $geocode = urldecode($geocode);

        return $geocode;
    }

    public function getLatLong(Request $rq)
    {
        try
        {
            return $this->parseAddress($rq->address);
        }
        catch (Exception $e) 
        {
            return back()->with(['msg_class' => 'error', 'msg_error' => 'Failed to retrieve lat/long from address.']);
        }
    }
}
