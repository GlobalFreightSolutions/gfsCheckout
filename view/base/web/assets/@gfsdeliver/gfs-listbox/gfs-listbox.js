import{PolymerElement,html}from"../../@polymer/polymer/polymer-element.js";import{mixinBehaviors}from"../../@polymer/polymer/lib/legacy/class.js";import{IronMenuBehavior}from"../../@polymer/iron-menu-behavior/iron-menu-behavior.js";class GfsListbox extends mixinBehaviors([IronMenuBehavior],PolymerElement){static get template(){return html`
            <style include="gfs-styles">
                :host {
                    padding: 0;
                    border: 1px solid #ccc;
                    display: block;
                    border-radius: 3px;

                    @apply --gfs-listbox;
                }
            </style>

            <slot></slot>
        `}static get is(){return"gfs-listbox"}static get properties(){return{listItem:{type:Object}}}ready(){super.ready();this.setAttribute("role","listbox")}}window.customElements.define("gfs-listbox",GfsListbox);