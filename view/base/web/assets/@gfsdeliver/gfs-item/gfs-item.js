import{PolymerElement,html}from"../../@polymer/polymer/polymer-element.js";import{mixinBehaviors}from"../../@polymer/polymer/lib/legacy/class.js";import{IronButtonStateImpl}from"../../@polymer/iron-behaviors/iron-button-state.js";import{IronControlState}from"../../@polymer/iron-behaviors/iron-control-state.js";class GfsItem extends mixinBehaviors([IronButtonStateImpl,IronControlState],PolymerElement){static get template(){return html`
            <style>
                :host {
                    padding: 10px;
                    display: block;
                    transition: all .4s ease-in-out;

                    --gfs-item-selected-color: var(--black-color);
                    --gfs-item-selected-background: rgba(236, 236, 236, 0.7);
                    --gfs-item-selected-font-weight: 600;

                    @apply --gfs-item;
                }

                :host(:hover) {
                    background: var(--gfs-item-hover-background, rgba(0, 0, 0, 0.08));
                    cursor: pointer;
                }

                :host(:focus), .gfs-item:focus {
                    outline: none;

                    @apply --gfs-item-focused;
                }

                :host([disabled]), .paper-item[disabled] {
                    color: rgba(0, 0, 0, .35)

                    @apply --gfs-item-disabled;
                }

                :host([active]), :host(.iron-selected) {
                    color: var(--gfs-item-selected-color);
                    background: var(--gfs-item-selected-background);
                    font-weight: var(--gfs-item-selected-font-weight);

                    @apply --gfs-item-selected;
                }

                :host(:first-child) {
                    @apply --gfs-item-first-child;
                }

                :host(:last-child) {
                    @apply --gfs-item-last-child;
                }

                iron-icon {
                    margin: 0 4px 0 0;
                }
            </style>

            <slot></slot>
            <dom-if if={{icon}}>
                <template>
                    <iron-icon icon="{{icon}}"></iron-icon><slot></slot>
                </template>
            </dom-if>

            <dom-if if={{!icon}}>
                <template>
                    <slot></slot>
                </template>
            </dom-if>
        `}static get is(){return"gfs-item"}static get properties(){return{active:Boolean,disabled:Boolean,icon:{type:String,value:""},listItem:{type:Object}}}connectedCallback(){super.connectedCallback();this.setAttribute("role","option");this.setAttribute("tabindex","0");this._itemClickHandler=event=>{this._onClick(event)};this.addEventListener("click",this._itemClickHandler)}disconnectedCallback(){if(this._itemClickHandler){this.removeEventListener("click",this._itemClickHandler);this._itemClickHandler=null}}_onClick(){const item=this,event=new CustomEvent("item-click",{bubbles:!0,composed:!0,detail:{item}});this.dispatchEvent(event)}}window.customElements.define("gfs-item",GfsItem);