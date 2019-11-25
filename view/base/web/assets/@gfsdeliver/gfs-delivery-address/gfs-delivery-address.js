import{PolymerElement,html}from"../../@polymer/polymer/polymer-element.js";import"../../@polymer/polymer/lib/elements/dom-if.js";import{format}from"../../date-fns/esm/index.js";import"./gfs-delivery-address-style.js";class GfsDeliveryAddress extends PolymerElement{static get template(){return html`
            <style include="gfs-delivery-address-style"></style>

            <div>
                <h3>{{title}}</h3>
                <div class="info">
                    <dom-if if="{{overrideStreetAddress}}">
                        <template>
                            <div class="infoText"><strong>Your order will be delivered to:</strong> </div>
                            <div id="deliveryAddress" class="delivery-address">{{overrideStreetAddress}}</div>
                        </template>
                    </dom-if>

                    <dom-if if="{{!overrideStreetAddress}}">
                        <template>
                            <div class="infoText"><strong>Your order will be delivered to:</strong></div>
                            <div id="deliveryAddress" class="delivery-address">{{defaultStreetAddress}}</div>
                        </template>
                    </dom-if>

                    <dom-if if="{{!selectedService}}">
                        <template>
                            <div class="infoText"><strong>Your chosen delivery service:</strong></div>
                            <div class="delivery-address red">{{notSelectedService}}</div>
                        </template>
                    </dom-if>

                    <dom-if if="{{selectedService}}">
                        <template>
                            <div class="infoText"><strong>Your chosen delivery service:</strong></div>
                            <div id="selectedService" class="delivery-address">{{selectedService}}
                                <dom-if if="{{!calendarService}}">
                                    <template>
                                        <strong>Delivery on: </strong>
                                    </template>
                                </dom-if>

                                <dom-if if="{{calendarService}}">
                                    <template>
                                        <strong>Est. arrival: </strong>
                                    </template>
                                </dom-if>
                            </div>
                        </template>
                    </dom-if>
                </div>
            </div>
        `}static get properties(){return{title:{type:String,value:"Your Delivery"},defaultStreetAddress:{type:String},overrideStreetAddress:{type:String,value:null},_visible:{type:Boolean,value:!1},selectedService:{type:String,value:null},notSelectedService:{type:String,value:"You have not chosen a delivery service"}}}ready(){super.ready()}connectedCallback(){super.connectedCallback();window.addEventListener("droppoint-changed",this.onDroppointChanged.bind(this),!1);window.addEventListener("getStandardSelectedService",this._getStandardSelectedService.bind(this),!1);window.addEventListener("getCalendarSelectedService",this._getCalendarSelectedService.bind(this),!1);window.addEventListener("getDroppointSelectedService",this._getDroppointSelectedService.bind(this),!1);window.addEventListener("getStoreSelectedService",this._getDroppointSelectedService.bind(this),!1)}disconnectedCallback(){super.disconnectedCallback();window.removeEventListener("droppoint-changed",this.onDroppointChanged.bind(this),!1);window.removeEventListener("getStandardSelectedService",this._getStandardSelectedService.bind(this),!1);window.removeEventListener("getCalendarSelectedService",this._getCalendarSelectedService.bind(this),!1);window.removeEventListener("getDroppointSelectedService",this._getDroppointSelectedService.bind(this),!1);window.removeEventListener("getStoreSelectedService",this._getDroppointSelectedService.bind(this),!1)}onDroppointChanged(e){this.set("droppointData",e.detail.data);if(!0===this.droppointData.chosen){this.overrideStreetAddress=JSON.stringify(this.droppointData.address).replace(/[\[\]"]+/g,"")}else{this.overrideStreetAddress=this.defaultStreetAddress}}onStoreChanged(e){this.set("storeData",e.detail);if(!0===this.storeData.chosen){this.overrideStreetAddress=this.storeData.geoLocation.addressLines+", "+this.storeData.geoLocation.town+", "+this.storeData.geoLocation.postCode}else{this.overrideStreetAddress=this.defaultStreetAddress}}_getStandardSelectedService(e){this._visible=e.detail.data.checked;if(0<Object.keys(e.detail.data).length){this.selectedService=e.detail.data.service+" ("+e.detail.data.currencySymbol+e.detail.data.shipping.price.toFixed(2)+")"+", "+"Est. arrival: "+format(new Date(e.detail.data.expDeliveryDateStart),"EEE, do MMMM")+" - "+format(new Date(e.detail.data.expDeliveryDateEnd),"EEE, do MMMM")}else{this.selectedService=null}}_getCalendarSelectedService(e){this._visible=e.detail.data.checked;if(0<Object.keys(e.detail.data).length){this.selectedService=e.detail.data.service+" ("+e.detail.data.currencySymbol+e.detail.data.shipping.price.toFixed(2)+")"+", "+"Delivery on: "+format(new Date(e.detail.data.deliveryTime),"EEE, do MMMM")}else{this.selectedService=null}}_getDroppointSelectedService(e){if(!!e.detail.data){let startDate=new Date(e.detail.data.expDeliveryDateStart).setHours(0,0,0),endDate=new Date(e.detail.data.expDeliveryDateEnd).setHours(0,0,0);this._visible=e.detail.data.chosen;if(0<Object.keys(e.detail.data).length){if(startDate===endDate){this.selectedService=e.detail.data.service+" ("+e.detail.data.currencySymbol+e.detail.data.shipping.price.toFixed(2)+")"+", "+"Est. arrival: "+format(new Date(e.detail.data.expDeliveryDateStart),"EEE, do MMMM")}else{this.selectedService=e.detail.data.service+" ("+e.detail.data.currencySymbol+e.detail.data.shipping.price.toFixed(2)+")"+", "+"Est. arrival: "+format(new Date(e.detail.data.expDeliveryDateStart),"EEE, do MMMM")+" - "+format(new Date(e.detail.data.expDeliveryDateEnd),"EEE, do MMMM")}}else{this.selectedService=null}}else{this.selectedService=null}}}window.customElements.define("gfs-delivery-address",GfsDeliveryAddress);