import{PolymerElement,html}from"../../@polymer/polymer/polymer-element.js";import"../../@polymer/paper-spinner/paper-spinner.js";import"../../@polymer/iron-ajax/iron-ajax.js";import"../gfs-toast/gfs-toast.js";import"../gfs-button/gfs-button.js";import"./gfs-droppoint-styles.js";export class GfsDroppointMap extends PolymerElement{static get template(){return html`
            <style include="gfs-droppoint-styles">
                :host {
                    width: 100%;
                    display: block;
                    position: relative;
                }
            </style>

            <iron-ajax id="getDroppointInfo"
                        method="GET" handle-as="json"
                        content-type="application/json"
                        on-response="_handleDroppointInfoResponse"
                        on-error="_handleError"
                        timeout="10000">
            </iron-ajax>

            <iron-ajax id="ajaxGetPostcode"
                        method="GET"
                        handle-as="json"
                        content-type="application/json"
                        on-response="_handleGetPostcodeResponse"
                        on-error="_handleError"
                        timeout="10000">
            </iron-ajax>

            <iron-ajax id="getOpeningHours"
                       method="GET" handle-as="json"
                       content-type="application/json"
                       on-response="_handleOpeningHoursResponse"
                       on-error="_handleError"
                       timeout="10000">
            </iron-ajax>

            <iron-ajax id="ajaxPostPostcode"
                        method="POST"
                        handle-as="json"
                        content-type="application/json"
                        on-response="_handlePostPostcodeResponse">
            </iron-ajax>

            <div id="dropPointLocation">
                <div id="droppointMap" style="height: {{mapHeight}}px"></div>
            </div>

            <div class="hidden">
                <div id="droppointInfoWindow">
                    <div id="loader">
                        <paper-spinner active></paper-spinner>
                    </div>

                    <div class$="{{isVisible}} carrier-icon">
                        <img src="{{carrierIcon}}" />
                    </div>
                    <div id="dp-address">
                        <div class="store-name-map">
                            {{droppointData.title}}
                        </div>
                        <div class="store-address-wrap">
                            <iron-icon icon="maps:my-location"></iron-icon>
                            <dom-repeat items="{{droppointData.address}}">
                                <template>
                                    <div class="store-address-map">
                                        {{item}},
                                    </div>
                                </template>
                            </dom-repeat>

                            <div class="store-location-map">
                                {{droppointData.town}}, {{droppointData.zip}}
                            </div>
                        </div>
                    </div>

                    <gfs-button class="default choose-droppoint map-btn" on-click="_getOpeningHours">View Opening Times</gfs-button>

                    <div class="weekCollection">
                        <template is="dom-repeat" items="{{_weekCollection}}">
                            <div class="wrap-opening-hours">
                                <div class="dp-day-name">
                                    <ul>
                                        <li>{{item.dayOfWeek}}</li>
                                    </ul>
                                </div>
                                <div class="dp-day-time-slots">
                                    <ul>
                                        <template is="dom-repeat" items="{{item.slots}}">
                                            <li class="dp-day-time-slot">{{item.fromTime}} - {{item.toTime}}</li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div id="dropPointDetails" class$="{{_droppointDetailsClass}}">
                <div id="droppoint_overlay" style="height: {{mapHeight}}px; display: block">
                    <div id="droppoint_overlay_close" on-click="_hideDroppointDetails">
                        <img src="//gfswidgetcdn.azurewebsites.net/images/widgets/x.png" alt="close" />
                    </div>
                    <gfs-droppoint id="overlay-droppoint" checkout-token="[[checkoutToken]]" droppoint-data="{{_selectedDroppoint}}" checkout-uri="{{checkoutUri}}" container-class="overlay" show-opening-hours></gfs-droppoint>
                </div>
            </div>

            <gfs-toast error id="notificationError"></gfs-toast>
        `}static get properties(){return{token:String,countryCode:String,postCode:String,carrierIcon:String,droppointData:{type:Object,value:{}},carriers:{type:Array,value:function(){return[]}},searchResultText:{type:String,value:"Last searched postcode:"},map:Object,mapHeight:{type:String,value:500},startingZoomLevel:{type:Number,value:14},endingZoomLevel:{type:Number,value:4},storeMapIcon:String,checkoutToken:{type:String,value:""},checkoutRequest:String,_infoWindow:Object,_droppointDetailsClass:{type:String,value:"hidden"},_markers:{type:Array,value:[]},_droppointMarkers:{type:Array,value:[]},_storeMarkers:{type:Array,value:[]},checkoutUri:String,checkoutToken:String}}static get observers(){return[]}connectedCallback(){super.connectedCallback();window.addEventListener("newDroppoints",this._loadMarkers.bind(this));window.addEventListener("droppoint-selected",this._droppointSelected.bind(this));window.addEventListener("droppoint-unselected",this._droppointUnSelected.bind(this));window.addEventListener("clearMarkers",this._clearMarkers.bind(this));window.addEventListener("loadDroppointMarkers",this._loadMarkers.bind(this))}disconnectedCallback(){super.disconnectedCallback();window.removeEventListener("newDroppoints",this._loadMarkers.bind(this));window.removeEventListener("droppoint-selected",this._droppointSelected.bind(this));window.removeEventListener("droppoint-unselected",this._droppointUnSelected.bind(this));window.removeEventListener("clearMarkers",this._clearMarkers.bind(this));window.removeEventListener("loadDroppointMarkers",this._loadMarkers.bind(this))}ready(){super.ready();this.buildDroppointMap()}buildDroppointMap(){var elem=this,mapOptions={zoom:this.startingZoomLevel,minZoom:this.startingZoomLevel-this.endingZoomLevel,mapTypeId:google.maps.MapTypeId.TERRAIN,streetViewControl:!1,disableDefaultUI:!1,mapTypeControl:!0,mapTypeControlOptions:{style:google.maps.MapTypeControlStyle.DROPDOWN_MENU,position:google.maps.ControlPosition.TOP_RIGHT},zoomControlOptions:{style:google.maps.ZoomControlStyle.SMALL,position:google.maps.ControlPosition.TOP_RIGHT},styles:[{featureType:"poi",elementType:"labels",stylers:[{visibility:"off"}]}]};this._infoWindow=new google.maps.InfoWindow({content:elem.$.droppointInfoWindow});this._map=new google.maps.Map(this.shadowRoot.querySelector("#droppointMap"),mapOptions);const geocoder=new google.maps.Geocoder;geocoder.geocode({componentRestrictions:{country:this.countryCode,postalCode:this.postCode}},(results,status)=>{if(status==google.maps.GeocoderStatus.OK){let homeMarkerConfig={position:results[0].geometry.location,animation:google.maps.Animation.DROP,title:"Home",html:"Home",icon:this._getHomeIcon()},marker=new google.maps.Marker(homeMarkerConfig);this._map.setCenter(homeMarkerConfig.position);marker.setMap(this._map)}else{console.log("Google Maps API ERROR - Geocode was not successful for the following reason: "+status);this.$.notificationError.text="Google Maps API ERROR - Geocode was not successful for the following reason: "+status;this.$.notificationError.duration=5e3;this.$.notificationError.open()}})}_getHomeIcon(){if(this.homeIcon){return this.homeIcon}else{return this.resolveUrl("//gfswidgetcdn.azurewebsites.net/images/widgets2.0/home.png")}}_loadMarkers(checkoutData){let _elem=this,markers=checkoutData.detail.data,isDroppoint=checkoutData.detail.droppoint,isStore=checkoutData.detail.store;markers.forEach(pointItem=>{let getCountryCode=_elem.countryCode;pointItem.countryCode=_elem.countryCode;pointItem.isDroppoint=isDroppoint;pointItem.isStore=isStore;let markerConfig={position:new google.maps.LatLng(pointItem.geo.lat,pointItem.geo.long),map:this._map,animation:google.maps.Animation.vn,title:pointItem.title,customData:pointItem,icon:isDroppoint?this._droppointMapIco(getCountryCode)+"/"+pointItem.provider.toLowerCase()+".png":this._storeIcon(),zIndex:google.maps.Marker.MAX_ZINDEX+1},marker=new google.maps.Marker(markerConfig);isDroppoint?this.push("_droppointMarkers",marker):this.push("_storeMarkers",marker);marker.addListener("click",function(){_elem._selectDroppoint(this,isDroppoint,isStore);const icon=isDroppoint?_elem._droppointMapIco(getCountryCode)+"/"+pointItem.provider.toLowerCase()+"-selected.png":_elem._storeIcon();this.setIcon(icon);_elem._getDroppointsDetails(this.customData.id,isDroppoint);_elem._infoWindow.open(_elem._map,this);_elem._infoWindow.opened=!0;_elem._map.panTo(this.customData.marker.getPosition());_elem.set("_weekCollection","");_elem.$.loader.style.display="block"});pointItem.marker=marker});google.maps.event.addListener(this._map,"click",()=>{this._clearSelectedDroppoint()});google.maps.event.addListener(this._infoWindow,"closeclick",()=>{this._unselectDroppoint()})}_droppointMapIco(countryCode){var url=this.resolveUrl("//gfswidgetcdn.azurewebsites.net/images/widgets2.0/carriers/");switch(countryCode){case"DE":return url+"DE";case"FR":case"BE":case"ES":return url+"FR";default:return url+"GB";}}_droppointInfoWindowIco(countryCode,provider){let url;if("DPD"===provider){provider="dpdpickup"}if("dpdpickup"===provider||"HERMES"===provider||"DODDLE"===provider||"COLLECT PLUS"===provider||"INPOST"===provider){url=this.resolveUrl("//gfswidgetcdn.azurewebsites.net/images/widgets/carriers");switch(countryCode){case"DE":return url+"/DE/"+provider.toLowerCase()+".png";case"FR":case"BE":case"ES":return url+"/FR/"+provider.toLowerCase()+".png";default:return url+"/GB/"+provider.toLowerCase()+".png";}}}_selectDroppoint(marker,isDroppoint,isStore){marker.customData.chosen=!0;this._selectedDroppoint=marker.customData;this._hideDroppointDetails();if(isDroppoint){this._fire("droppoint-changed",marker.customData,isDroppoint,isStore)}else{this._fire("store-changed",marker.customData,isDroppoint,isStore)}}_unselectDroppoint(){if(this._selectedDroppoint){this._selectedDroppoint.chosen=!1;if(this._selectedDroppoint.isDroppoint){let icon=this._droppointMapIco(this.countryCode)+"/"+this._selectedDroppoint.provider.toLowerCase()+".png";this._selectedDroppoint.marker.setIcon(icon)}this._fire("droppoint-changed",this._selectedDroppoint);if(this._currentMarker){this._currentMarker.setAnimation(null)}if(this._infoWindow){this._infoWindow.close();this._infoWindow.opened=!1}}}_clearSelectedDroppoint(){if(this._selectedDroppoint){this._selectedDroppoint.chosen=!1;if(this._selectedDroppoint.isDroppoint){let icon=this._droppointMapIco(this.countryCode)+"/"+this._selectedDroppoint.provider.toLowerCase()+".png";this._selectedDroppoint.marker.setIcon(icon)}if(this._currentMarker){this._currentMarker.setAnimation(null)}if(this._infoWindow){this._infoWindow.close();this._infoWindow.opened=!1}this._fire("clear-selected-droppoint")}}_droppointSelected(e){let isDroppoint=e.detail.droppoint,countryCode=e.detail.data.countryCode,icon=isDroppoint?this._droppointMapIco(countryCode)+"/"+e.detail.data.provider.toLowerCase()+"-selected.png":this._storeIcon();e.detail.data.marker.setIcon(icon);this._selectedDroppoint=e.detail.data;this.droppointData=e.detail.data;if(isDroppoint){this.carrierIcon=this._droppointInfoWindowIco(e.detail.data.countryCode,e.detail.data.provider);this.isVisible=""}else{this.isVisible="hidden"}this._infoWindow.open(this._map,e.detail.data.marker);this._infoWindow.opened=!0;this._weekCollection=null}_droppointUnSelected(e){let isDroppoint=e.detail.droppoint,countryCode=e.detail.data.countryCode,icon=isDroppoint?this._droppointMapIco(countryCode)+"/"+e.detail.data.provider.toLowerCase()+".png":this._storeIcon();e.detail.data.marker.setIcon(icon)}_searchPostcode(e,gfsCheckoutElem){e.preventDefault();const target=e.currentTarget,elem=e.currentTarget.id;var ddSearchTerms=e.target.parentElement.parentElement.querySelector("#droppointAddress").value;const checkoutRequest=JSON.parse(atob(gfsCheckoutElem.checkoutRequest)),postcode=checkoutRequest.order.delivery.destination.zip;if(postcode!==ddSearchTerms){if(this.useDroppointsStores){this.shadowRoot.querySelector("#toggleStoreOnMap").checked=!1;this.shadowRoot.querySelector("#toggleDroppointsOnMap").checked=!1;this._storeDetailsClass="fade-out";this._droppointDetailsClass="fade-out"}this._fire("hide-collectionInfo");switch(elem){case"droppointSubmit":return[e.target.parentElement.parentElement.querySelector("#lastddPostcode").innerText=this.searchResultText+" "+ddSearchTerms,this._returnPostcode(ddSearchTerms,"dropPoint",target,gfsCheckoutElem),ddSearchTerms=""];case"storeSubmit":return[e.target.parentElement.parentElement.querySelector("#lastStorePostcode").innerText="Last searched postcode: "+this.$.storeAddress.value,this._returnPostcode(this.$.storeAddress,"store",target,gfsCheckoutElem),this.$.storeAddress=""];default:}}else{gfsCheckoutElem.shadowRoot.querySelectorAll("#mapLoader")[0].style.display="none"}}_returnPostcode(postcode,deliveryType,target,gfsCheckoutElem){var emptyPostcode=new RegExp(/^\s\s*/),obj={},detail={};if(""===postcode||postcode.match(emptyPostcode)){this.$.notificationError.text="Please enter a valid postcode";this.$.notificationError.fitInto=this.$.gfsMapCanvas;this.$.notificationError.duration=5e3;this.$.notificationError.open();return}if(!!this._droppointMarkers&&"dropPoint"===deliveryType){detail.data="dropPointMap";detail.droppoint=!0;detail.store=!1;obj.detail=detail;this._clearMarkers(obj);if(this.useDroppointsStores){detail.data="dropPointMap";detail.droppoint=!1;detail.store=!0;obj.detail=detail;this._clearMarkers(obj)}}const geocoder=new google.maps.Geocoder;geocoder.geocode({componentRestrictions:{country:this.countryCode,postalCode:postcode}},(results,status)=>{if(status==google.maps.GeocoderStatus.OK){var position=results[0].geometry.location;if("dropPoint"===deliveryType){this._map.setCenter(position)}else{this._storeMap.setCenter(position)}const checkoutRequest=JSON.parse(atob(gfsCheckoutElem.checkoutRequest));checkoutRequest.order.delivery.destination.zip=postcode;gfsCheckoutElem.checkoutRequest=btoa(JSON.stringify(checkoutRequest))}else{console.log("Google Maps API ERROR - Geocode was not successful for the following reason: "+status);this.$.notificationError.text="Postcode not found in "+this.countryCode;this.$.notificationError.classList.remove("fit-top");this.$.notificationError.horizontalAlign="left";this.$.notificationError.verticalAlign="top";gfsCheckoutElem.shadowRoot.querySelectorAll("#mapLoader")[0].style.display="none";const checkoutData={detail:{}};if("dropPoint"===deliveryType){this.$.notificationError.fitInto=this.$.droppointMap;checkoutData.detail.droppoint=!0;checkoutData.detail.store=!1;checkoutData.detail.data=gfsCheckoutElem.dropPoints;this._loadMarkers(checkoutData)}else{this.$.notificationError.fitInto=this.$.storeMapCanvas;checkoutData.detail.droppoint=!1;checkoutData.detail.store=!0;checkoutData.detail.data=gfsCheckoutElem.stores;this._loadMarkers(checkoutData)}this.$.notificationError.duration=5e3;this.$.notificationError.open();target.disabled=!1;return}target.disabled=!1})}_hideDropPoints(obj,val){this._clearMarkers(obj,val)}_storeIcon(){var icon;if(!!this.storeMapIcon){icon=this.resolveUrl(this.storeMapIcon)}else{icon=this.resolveUrl("//gfswidgetcdn.azurewebsites.net/images/widgets2.0/store-map-icon.png")}return icon}_clearMarkers(obj){let markers,isDroppoint=obj.detail.droppoint;if(isDroppoint){markers=this._droppointMarkers;for(var i=0;i<markers.length;i++){markers[i].setMap(null)}}else{markers=this._storeMarkers;for(var i=0;i<markers.length;i++){markers[i].setMap(null)}}}_showDroppointDetails(){this.mapHeight=this.$.droppointMap.clientHeight;this._storeDetailsClass="fade-out";this._droppointDetailsClass="fade-in";this._fire("show-opening-hours",this._selectedDroppoint)}_hideDroppointDetails(){this._droppointDetailsClass="fade-out"}_getDroppointsDetails(droppointID,isDroppoint){this.isDroppoint=isDroppoint;let createSession=this.$.getDroppointInfo;createSession.url=this.checkoutUri+(isDroppoint?"/api/droppoints/":"/api/stores/")+droppointID;createSession.headers=this._getBearerToken();createSession.generateRequest()}_getOpeningHours(){if(this.droppointData.weekCollection==void 0&&!!!this._selectedDroppoint.weekCollection){let createSession=this.$.getOpeningHours;createSession.url=this.checkoutUri+this._selectedDroppoint.detail;createSession.headers=this._getBearerToken();createSession.generateRequest();this.shadowRoot.querySelector("#loader").style.display="block"}else{this._weekCollection=this.droppointData.weekCollection?this.droppointData.weekCollection:this._selectedDroppoint.weekCollection}}_fire(ev,el,isDroppoint,isStore){this.dispatchEvent(new CustomEvent(ev,{bubbles:!0,composed:!0,detail:{data:el,droppoint:isDroppoint,store:isStore}}))}_getBearerToken(){return{Authorization:"Bearer "+atob(this.checkoutToken)}}_handleDroppointInfoResponse(e){this.droppointData=e.detail.response;if(this.isDroppoint){this.shadowRoot.querySelector(".carrier-icon").style.display="block";this.carrierIcon=this._droppointInfoWindowIco(e.detail.response.country,e.detail.response.provider)}else{this.shadowRoot.querySelector(".carrier-icon").style.display="none"}this.$.loader.style.display="none"}_handleOpeningHoursResponse(e){var buildWeekCollection=[],dayCount=0;this._weekCollection=[];e.detail.response.days.forEach(collectionSlot=>{dayCount++;if(7>=dayCount){collectionSlot.slots.forEach(slot=>{slot.fromTime=slot.from;slot.toTime=slot.to});if(0<collectionSlot.slots.length){buildWeekCollection.push(collectionSlot)}}});this.set("_weekCollection",buildWeekCollection);this.droppointData.weekCollection=this._weekCollection;this._selectedDroppoint.weekCollection=this._weekCollection;this.shadowRoot.querySelector("#loader").style.display="none"}_handleError(e){if("timeout"===e.detail.error.type){this.$.notificationError.text="Server Took Too Long To Respond";this.$.notificationError.classList.add("fit-top");this.$.notificationError.open();this.$.loader.style.display="none"}else{console.error("Error: ",e.detail.error.message);this.$.notificationError.text=e.detail.error.message;this.$.notificationError.classList.add("fit-top");this.$.notificationError.open();this.$.loader.style.display="none"}}}window.customElements.define("gfs-droppoint-map",GfsDroppointMap);