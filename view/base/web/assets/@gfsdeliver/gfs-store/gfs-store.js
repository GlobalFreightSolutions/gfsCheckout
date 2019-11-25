import{PolymerElement,html}from"../../@polymer/polymer/polymer-element.js";import"../../@polymer/iron-icon/iron-icon.js";import"../../@polymer/iron-icons/iron-icons.js";import"../../@polymer/iron-icons/device-icons.js";import"../../@polymer/iron-icons/maps-icons.js";import"../gfs-button/gfs-button.js";import"../gfs-toast/gfs-toast.js";import"./gfs-store-styles.js";export class GfsStore extends PolymerElement{static get template(){return html`
            <style include="gfs-store-styles">
                :host {
                    --gfs-button-padding: 5px 10px;
                }
            </style>

            <iron-ajax id="getOpeningHours"
                       method="GET" handle-as="json"
                       content-type="application/json"
                       on-response="_handleOpeningHoursResponse"
                       on-error="_handleError"
                       timeout="10000">
            </iron-ajax>

            <div class$="{{containerClass}} dp-card">
                <dom-if if="{{title}}">
                    <template>
                        <h3>{{title}}</h3>
                    </template>
                </dom-if>
                <div class="content">
                    <div class="dp-details">
                        <dom-if if="{{showDistance}}">
                            <template>
                                <div class="dp-small-heading">
                                    <iron-icon icon="maps:navigation" class="distance-ico"></iron-icon> Distance:
                                    <div id="dp-distance">
                                        {{storeData.geo.distance}}m
                                    </div>
                                </div>

                            </template>
                        </dom-if>

                        <dom-if if="{{showOpeningHours}}">
                            <template>
                                <div class="dp-opening-hours-heading">
                                    <div class="dp-small-heading opening-hours" on-click="_getOpeningHours">
                                        <iron-icon icon="device:access-time"></iron-icon> Opening Hours
                                    </div>
                                </div>
                            </template>
                        </dom-if>
                    </div> <!-- .dp-details -->

                    <div class="address-wrap">
                        <div id="dp-address">
                            <div class="store-name">
                                <iron-icon icon="maps:my-location"></iron-icon> {{storeDescription}} {{storeId}}
                            </div>
                            <div class="store-address">
                                <dom-repeat items="{{storeData.address}}">
                                    <template>
                                        {{item}},
                                    </template>
                                </dom-repeat>
                            </div>
                        </div>
                    </div>
                </div>  <!-- .content -->

                <dom-if if="{{showOpeningHours}}">
                    <template>
                        <div class="dp-opening-hours">
                            <div id="loader">
                                <paper-spinner active></paper-spinner>
                            </div>
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
                    </template>
                </dom-if>

                <div class="dd-action">
                    <dom-if if="{{isStandardButton}}">
                        <template>
                            <gfs-button on-click="toggleChooseStore" class$="default choose-store {{buttonClass}}">
                                {{displayButtonText}}
                            </gfs-button>
                        </template>
                    </dom-if>
                </div>
            </div>  <!-- .dp-card -->

            <gfs-toast error id="notificationError"></gfs-toast>
        `}static get properties(){return{isMap:{type:Boolean,notify:!0},countryCode:String,postCode:String,orientation:{type:String,notify:!0},containerClass:{type:String,value:""},storeId:String,storeData:{type:Object,value:{}},showOpeningHours:Boolean,showDistance:Boolean,isStandardButton:Boolean,buttonDeselectedText:{type:String,value:"Deselect"},streetAddress:{type:String,notify:!0},providerLogo:String,providerName:String,checkoutUri:String,checkoutToken:String,_selectedStore:Object,_weekDays:{type:Array,value:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"]},_weekCollection:{type:Object,notify:!0}}}static get observers(){return["_reOrderStore(storeData)"]}connectedCallback(){super.connectedCallback();document.addEventListener("store-changed",this.onStoreChanged.bind(this),!1);document.addEventListener("droppoint-changed",this.onDroppointChanged.bind(this),!1);document.addEventListener("store-unselected",this.onStoreChanged.bind(this),!1);document.addEventListener("show-opening-hours",this.showStoreOpeningHours.bind(this),!1)}disconnectedCallback(){super.disconnectedCallback();document.removeEventListener("store-changed",this.onStoreChanged.bind(this),!1);document.removeEventListener("droppoint-changed",this.onDroppointChanged.bind(this),!1);document.removeEventListener("store-unselected",this.onStoreChanged.bind(this),!1);document.removeEventListener("show-opening-hours",this.showStoreOpeningHours.bind(this),!1)}ready(){super.ready()}showList(val){this.isMap=val.detail.isMap}initData(){this.storeId=this.storeData.id||"";if("undefined"===typeof this.storeData.chosen){this.storeChosen=!1}this.providerName=this.storeData.provider;this.providerLogo=this.countryCode;if(0<Object.keys(this.storeData).length){this.storeDescription=this.storeData.title.toLowerCase();this.storeData.storeDescription=this.storeData.title.toLowerCase()}}onStoreChanged(e){if(e.detail.store){if(this._selectedStore){if(e.detail.data.id!==this._selectedStore.data.id){this._unselectStore()}}this._selectedStore=e.detail;this.render()}}onDroppointChanged(e){if(this._selectedDroppoint){this._selectedDroppoint.data.chosen=!1}if(this._selectedStore){if(e.detail.data.id!==this._selectedStore.data.id){this._unselectStore()}}this._selectedStore=e.detail;this.render()}toggleChooseStore(){this.set("storeData.chosen",!this.storeData.chosen);this.chosenStoreChanged()}chosenStoreChanged(){if("null"===typeof this.storeData.chosen)return;if(this.storeData.marker.customData.isStore){this.dispatchEvent(new CustomEvent("store-changed",{bubbles:!0,composed:!0,detail:{data:this.storeData,droppoint:this.storeData.marker.customData.isDroppoint,store:this.storeData.marker.customData.isStore}}))}else{this.dispatchEvent(new CustomEvent("droppoint-changed",{bubbles:!0,composed:!0,detail:{data:this.storeData,droppoint:this.storeData.marker.customData.isDroppoint,store:this.storeData.marker.customData.isStore}}))}}render(){this.initData();if(this.storeData.chosen){this.buttonClass="chosen";this.displayButtonText=this.buttonDeselectedText}else{this.buttonClass="unchosen";this.displayButtonText=this.buttonSelectedText}}_reOrderStore(){this.render()}_unselectStore(){if(this._selectedStore){this._selectedStore.data.chosen=!1}}showStoreOpeningHours(e){if(!e.detail.data.isStore){if(e.detail.data.id===this._selectedStore.data.id){this._getOpeningHours()}}}_getOpeningHours(){if(this.storeData.weekCollection==void 0){let createSession=this.$.getOpeningHours;createSession.url=this.checkoutUri+this.storeData.detail;createSession.headers=this._getBearerToken();createSession.generateRequest();this.shadowRoot.querySelector("#loader").style.display="block"}else{this._weekCollection=this.storeData.weekCollection?this.storeData.weekCollection:this._selectedStore.data.weekCollection}}_getBearerToken(){return{Authorization:"Bearer "+atob(this.checkoutToken)}}_handleOpeningHoursResponse(e){var buildWeekCollection=[],dayCount=0;this._weekCollection=[];e.detail.response.days.forEach(collectionSlot=>{dayCount++;if(7>=dayCount){collectionSlot.slots.forEach(slot=>{slot.fromTime=slot.from;slot.toTime=slot.to});if(0<collectionSlot.slots.length){buildWeekCollection.push(collectionSlot)}}});this.set("_weekCollection",buildWeekCollection);this.storeData.weekCollection=this._weekCollection;this.shadowRoot.querySelector("#loader").style.display="none"}_handleError(e){if("timeout"===e.detail.error.type){this.$.notificationError.text="Server Took Too Long To Respond";this.$.notificationError.classList.add("fit-top");this.$.notificationError.open();this.shadowRoot.querySelector("#loader").style.display="none"}else{console.error("Error: ",e.detail.error.message);this.$.notificationError.text=e.detail.error.message;this.$.notificationError.classList.add("fit-top");this.$.notificationError.open();this.shadowRoot.querySelector("#loader").style.display="none"}}}window.customElements.define("gfs-store",GfsStore);