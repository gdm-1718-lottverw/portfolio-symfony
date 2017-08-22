import {Component, NgZone, OnInit } from '@angular/core';
// MODELS 
import { ProjectStatistics      } from '../../_providers/statistics/project.model';
// SERVICES 
import { StatisticsService      } from '../../_providers/statistics/statistics.service';
import { MapsAPILoader          } from '@agm/core';
import { LocationService        } from "../../_providers/locations/location.service";
import { UserLocation           } from "../../_providers/locations/user-location.model";

declare var google: any;
@Component({
    selector: 'page-dashboard',
    styleUrls: ['./dashboard.css'],
    templateUrl: './dashboard.html',
    providers: [ StatisticsService, LocationService ]
})
export class DashboardPage implements OnInit{
    project_deadline_graph: Object; zoom = 15;
    styles = [
        {
            "featureType": "administrative",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#444444"
                }
            ]
        },
        {
            "featureType": "landscape",
            "elementType": "all",
            "stylers": [
                {
                    "color": "#f2f2f2"
                }
            ]
        },
        {
            "featureType": "poi",
            "elementType": "all",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "poi.business",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "visibility": "on"
                }
            ]
        },
        {
            "featureType": "road",
            "elementType": "all",
            "stylers": [
                {
                    "saturation": -100
                },
                {
                    "lightness": 45
                }
            ]
        },
        {
            "featureType": "road.highway",
            "elementType": "all",
            "stylers": [
                {
                    "visibility": "simplified"
                }
            ]
        },
        {
            "featureType": "road.arterial",
            "elementType": "labels.icon",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "transit",
            "elementType": "all",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "all",
            "stylers": [
                {
                    "color": "#b4d4e1"
                },
                {
                    "visibility": "on"
                }
            ]
        }
    ];

    lat = 51.194804; lng = 4.731351600000039; userLocation: UserLocation[] = [];
    project: ProjectStatistics = {};
    markers: Marker[] = [];
    constructor(
        public statSercvice: StatisticsService,
        public locationService: LocationService,
        public mapsAPILoader: MapsAPILoader,
        public ngZone: NgZone,
    ){}

    ngOnInit(){
        this.statSercvice.getProjects().subscribe(pro => { this.project = pro, this.getGraph() });
        this.locationService.getLocations().subscribe( ul => {
            this.userLocation = ul;
            this.userLocation.forEach(el => {
                console.log(el.location.adress);
                this.getCoords(el.location.adress, el.username);
            });
        });
    }

    getCoords(address,username){
        let me = this;
        this.mapsAPILoader.load().then(() => {
            let geocoder = new google.maps.Geocoder();
            geocoder.geocode({'address': address}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    me.markers.push({
                        label: username,
                        lat: results[0].geometry.location.lat(),
                        lng: results[0].geometry.location.lng(),
                        draggable: false
                    });
                } else {
                    console.log('Error - ', results, ' & Status - ', status);
                }

            });
        });
    }


   getGraph(){
        this.project_deadline_graph = {
            title : { text : false },
            chart: { type: 'pie', backgroundColor: null,
                margin: 0,
                height: 180,
                marginLeft: 27
            },
            exporting: { enabled: false },
            credits: {
                enabled: false
            },
            tooltip: {
                 pointFormat: '{series.name}: <b>{point.y}</b>'
            },
            plotOptions: {
                pie: {
                    dataLabels: {
                        enabled: false,
                    },
                    cursor: 'pointer',
                    shadow: false,
                    borderWidth: 0,
                    size: '100%'
                }
            },
            colors: [
                '#FF0000',
                '#00FF00',
                '#FF4500'
            ],
            series: [
                { name : 'project status', data: [ 
                    { 
                        name: "Needed more time",
                        y: this.project.red.length, 
                    },
                    {
                        name: "Project finished on time",
                        y: this.project.green.length, 
                    },
                    {  
                        name: "Unfinished projects", 
                        y: this.project.orange.length
                    }] 
                }
            ]
        }
    }
}

export class Marker {
    lat?: number;
    lng?: number;
    label?: string;
    draggable?: false;
}

