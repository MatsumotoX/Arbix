@extends('layouts.app')
@section('title','Dashboard')
    
@section('content')
    
<section class="progress-bars2 cid-r0kOe0qdGK" id="progress-bars2-7">
    
    <div class="container">
        <h2 class="mbr-section-title pb-3 align-center mbr-fonts-style display-2">
            EECL Dashboard

        <hr>

        <div class="row pt-5 mt-5">
            <div class="col-md-6 text-elements">
                <h4 class="section-content-title pb-3 align-left mbr-fonts-style display-5">
                    MFR 
                </h4>
                <p class="section-content-text align-left mbr-fonts-style display-7">
                    Make your own website in a few clicks! Mobirise helps you cut down development time by providing you with a flexible website editor with a drag and drop interface. Mobirise Website Builder creates responsive, retina and mobile friendly websites in a few clicks. Mobirise is one of the easiest website development tools available today. It also gives you the freedom to develop as many websites as you like given the fact that it is a desktop app.
                </p>
            </div>
            <div class="progress_elements col-md-6">
                <div class="pb-3 display-5">
                    <h4>Business Unit</h4>
                </div>
                <div class="progress1 pb-5">
                    <div class="title-wrap">
                        <div class="progressbar-title mbr-fonts-style display-7">
                            <p>
                                Amenity
                            </p>
                        </div>
                        <div class="progress_value mbr-fonts-styledd display-7">
                            <spans>100
                            </spans>
                            <span>%</span>
                        </div>
                    </div>
                    <progress class="progress progress-primary " max="100" value="30">
                    </progress>
                </div>
                 
                <div class="progress1 pb-5">
                    <div class="title-wrap">
                        <div class="progressbar-title mbr-fonts-style display-7">
                            <p>
                                Public transport
                            </p>
                        </div>
                    <div class="progress_value mbr-fonts-style display-7">
                        <div class="progressbar-number">
                        </div>
                        <span>%</span>
                    </div>
                    </div>
                    <progress class="progress progress-primary" max="100" value="90">
                    </progress>
                </div>
                
                <div class="progress3 pb-5">
                    <div class="title-wrap">
                        <div class="progressbar-title mbr-fonts-style display-7">
                            <p>
                                Nightlife
                            </p>
                        </div>
                    <div class="progress_value mbr-fonts-style display-7">
                        <div class="progressbar-number">
                        </div>
                        <span>%</span>
                    </div>
                    </div>
                    <progress class="progress progress-primary" max="100" value="80">
                    </progress>
                </div>


            
                
            </div>
        </div>
    </div>
</section>
@endsection