import{PolymerElement,html}from"../../@polymer/polymer/polymer-element.js";export class GfsCarrierIcon extends PolymerElement{static get template(){return html`
            <style>
                :host {
                    margin: 7px;

                    --image-border: none;
                    --image-shadow: none;
                }

                .icon {
                    display: inline-block;
                }

                .small img, .medium img, .large img, .largest img {
                    margin: 0;
                    border: var(--image-border);
                    box-shadow: var(--image-shadow);
                }

                .small img {
                    height: 50px;
                    margin: 0;
                    box-shadow: none;
                }

                .medium img {
                    height: 60px;
                }

                .large img {
                    height: 78px;
                }

                .largest img {
                    height: 100%;
                }

                @media (max-width: 614px) {
                    .small img {
                        height: 35px;
                    }

                    .medium img {
                        height: 52px;
                    }

                    .large img {
                        height: 62px;
                    }
                }

                @media (max-width: 490px) {
                    .icon:nth-child(1n) {
                        margin: 0 0 10px;
                    }
                }
            </style>

            <div class$="icon {{iconSize}}">
                <a href="{{carrierURL}}" target="_blank">
                    <img src="{{imagePath}}" title="{{carrierName}}" />
                </a>
            </div>
        `}static get properties(){return{carrierName:{type:String,notify:!0,reflectToAttribute:!0,observer:"onIconChanged"},countryCode:{type:String,value:"GB"},iconSize:{type:String,value:"medium"},imageUrl:{type:String},carrierURL:{type:String,observer:"onUrlChanged",notify:!0,reflectToAttribute:!0},url:{type:String,value:"url"},_carrierIndex:{type:Object,value:{dpd:{url:"https://www.dpd.com/"},doddle:{url:"https://www.doddle.com/"},collectplus:{url:"https://www.collectplus.co.uk/"},hermes:{url:"https://www.myhermes.co.uk/"},inpost:{url:"https://inpost.co.uk/"},dpdpickup:{url:"https://www.dpd.com/nl_privatecustomers#!pickup"},dpdpickupinfo:{url:"https://www.dpd.com/nl_privatecustomers#!pickup"},yodel:{url:"https://www.yodel.co.uk/"},anpost:{url:"http://www.anpost.ie/AnPost/"},arrowxl:{url:"https://www.arrowxl.co.uk/"},correos:{url:"https://www.correos.es/"},dhlexpress:{url:"http://parcel.dhl.co.uk/"},dhlfreight:{url:"http://parcel.dhl.co.uk/"},interlinkexpress:{url:"http://www.interlinkexpress.com/"},iparcel:{url:"https://www.i-parcel.com/"},itella:{url:"http://www.itella.ee/english/"},nightfreight:{url:"https://www.dxdelivery.com/"},nightline:{url:"http://www.nightline.ie/"},palletforce:{url:"http://www.palletforce.com/"},parcelforce:{url:"http://www.parcelforce.com/"},royalmail:{url:"http://www.royalmail.com/"},sanmarinomail:{url:"http://www.sanmarinomail.sm/"},seur:{url:"https://www.seur.com/en/"},spring:{url:"http://www.spring-gds.com/"},tuffnells:{url:"http://www.tuffnells.co.uk/home"},ukmail:{url:"https://www.ukmail.com/"}}}}}ready(){super.ready();this.carrierURL=this._carrierIndex[this.carrierName.replace(/[\s\_\-]/g,"").toLowerCase()][this.url];this.imageUrl=this._carrierIndex[this.carrierName.replace(/[\s\_\-]/g,"").toLowerCase()][this.iconSize]}onIconChanged(providerName){this.carrierURL=this._carrierIndex[this.carrierName.replace(/[\s\_\-]/g,"").toLowerCase()][this.url];this.imagePath=this._computeIcon(this.countryCode,providerName,this.iconSize)}onUrlChanged(carrierURL){if("https://www.myhermes.co.uk/"===carrierURL){switch(this.countryCode){case"FR":return this.carrierURL="http://www.mondialrelay.fr/";case"BE":return this.carrierURL="http://www.mondialrelay.be/fr-BE/";case"ES":return this.carrierURL="http://www.puntopack.es/";default:return this.carrierURL;}}}_computeIcon(countryCode,providerName,iconSize){const carrierLink=this.resolveUrl("//gfswidgetcdn.azurewebsites.net/images/widgets/carriers/");switch(countryCode.toUpperCase()){case"DE":return carrierLink+iconSize+"/DE/"+providerName.replace(/[\s\_\-]/g,"").toLowerCase()+".png";case"FR":case"BE":case"ES":return carrierLink+iconSize+"/FR/"+providerName.replace(/[\s\_\-]/g,"").toLowerCase()+".png";default:return carrierLink+iconSize+"/GB/"+providerName.replace(/[\s\_\-]/g,"").toLowerCase()+".png";}}}window.customElements.define("gfs-carrier-icon",GfsCarrierIcon);