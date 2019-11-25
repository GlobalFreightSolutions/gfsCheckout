import{PolymerElement,html}from"../../@polymer/polymer/polymer-element.js";import{IronOverlayBehavior,IronOverlayBehaviorImpl}from"../../@polymer/iron-overlay-behavior/iron-overlay-behavior.js";import{mixinBehaviors}from"../../@polymer/polymer/lib/legacy/class.js";const $_documentContainer=document.createElement("template");$_documentContainer.innerHTML=`<custom-style>
    <style>
        html {
            --gfs-toast-color: var(--white-color);
            --gfs-toast-background: var(--gfs-default-color);
            --gfs-toast-margin: 0 5px 0 0;
            --gfs-toast-font-size: 14px;
            --gfs-toast-font-family: "Segoe UI", 'Helvetica Neue';
            --gfs-toast-font-weight: normal;
            --gfs-toast-text-transform: normal;
            --gfs-toast-border-radius: 3px;
            --gfs-toast-box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.26);
        }
    </style>
</custom-style>`;document.head.appendChild($_documentContainer.content);class GfsToast extends mixinBehaviors(IronOverlayBehavior,PolymerElement){static get template(){return html`
            <style>
                :host {
                    min-height: 48px;
                    min-width: 300px;
                    background-color: var(--gfs-toast-background);
                    color: var(--gfs-toast-color);
                    margin: 12px;
                    padding: 16px 24px;
                    font-size: var(--gfs-toast-font-size);
                    font-weight: var(--gfs-toast-font-weight);
                    position: fixed;
                    opacity: 0;
                    box-sizing: border-box;
                    box-shadow: var(--gfs-toast-box-shadow);
                    border-radius: var(--gfs-toast-border-radius);
                    transform: translateY(100px);
                    -webkit-transform: translateY(100px);
                    transition: transform 0.3s, opacity 0.3s;
                    -webkit-transition: -webkit-transform 0.3s, opacity 0.3s;
                }

                :host([info]) {
                    color: #31708f;
                    background-color: #d9edf7;
                    border: 1px solid #bce8f1;
                }

                :host([success]) {
                    color: #3c763d;
                    background-color: #dff0d8;
                    border: 1px solid #d6e9c6;
                }

                :host([warning]) {
                    color: #8a6d3b;
                    background-color: #fcf8e3;
                    border: 1px solid #faebcc;
                }

                :host([error]) {
                    color: #a94442;
                    background-color: #f2dede;
                    border: 1px solid #ebccd1;
                }

                :host(.fit-top), :host(.fit-bottom) {
                    width: 100%;
                    min-width: 0;
                    margin: 0;
                    border: none;
                    border-radius: 0;
                }

                :host(.fit-top) {
                    top: 0 !important;
                }

                :host(.gfs-toast-open) {
                    opacity: 1;
                    transform: translateY(0px);
                    -webkit-transform: translateY(0px);
                }
            </style>

            <span id="label">{{text}}</span>
            <slot></slot>
        `}static get is(){return"gfs-toast"}static get properties(){return{text:String,horizontalAlign:{type:String,value:"left"},verticalAlign:{type:String,value:"bottom"},fitInto:{type:Object,value:window,observer:"_onFitIntoChanged"},duration:{type:Number,value:3500},noCancelOnOutsideClick:{type:Boolean,value:!0}}}get _canAutoClose(){return 0<this.duration}connectedCallback(){super.connectedCallback();this._autoClose=null}ready(){super.ready()}show(){this.open()}hide(){this.close()}_openedChanged(){if(this.opened){if(this._canAutoClose){this._autoClose=this.async(this.close,this.duration)}}IronOverlayBehaviorImpl._openedChanged.apply(this,arguments)}_renderOpened(){this.classList.add("gfs-toast-open");this._finishRenderOpened()}_renderClosed(){this.classList.remove("gfs-toast-open");this._finishRenderClosed()}_onFitIntoChanged(fitInto){this.positionTarget=fitInto}}window.customElements.define(GfsToast.is,GfsToast);