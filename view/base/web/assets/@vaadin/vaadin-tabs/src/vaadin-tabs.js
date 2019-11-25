import{PolymerElement}from"../../../@polymer/polymer/polymer-element.js";import{ThemableMixin}from"../../vaadin-themable-mixin/vaadin-themable-mixin.js";import{ListMixin}from"../../vaadin-list-mixin/vaadin-list-mixin.js";import{IronResizableBehavior}from"../../../@polymer/iron-resizable-behavior/iron-resizable-behavior.js";import{ElementMixin}from"../../vaadin-element-mixin/vaadin-element-mixin.js";import"./vaadin-tab.js";import{html}from"../../../@polymer/polymer/lib/utils/html-tag.js";import{afterNextRender}from"../../../@polymer/polymer/lib/utils/render-status.js";import{mixinBehaviors}from"../../../@polymer/polymer/lib/legacy/class.js";const safari10=/Apple.* Version\/(9|10)/.test(navigator.userAgent);class TabsElement extends ElementMixin(ListMixin(ThemableMixin(mixinBehaviors([IronResizableBehavior],PolymerElement)))){static get template(){return html`
    <style>
      :host {
        display: flex;
        align-items: center;
      }

      :host([hidden]) {
        display: none !important;
      }

      :host([orientation="vertical"]) {
        display: block;
      }

      :host([orientation="horizontal"]) [part="tabs"] {
        flex-grow: 1;
        display: flex;
        align-self: stretch;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        -ms-overflow-style: none;
      }

      /* This seems more future-proof than \`overflow: -moz-scrollbars-none\` which is marked obsolete
         and is no longer guaranteed to work:
         https://developer.mozilla.org/en-US/docs/Web/CSS/overflow#Mozilla_Extensions */
      @-moz-document url-prefix() {
        :host([orientation="horizontal"]) [part="tabs"] {
          overflow: hidden;
        }
      }

      :host([orientation="horizontal"]) [part="tabs"]::-webkit-scrollbar {
        display: none;
      }

      :host([orientation="vertical"]) [part="tabs"] {
        height: 100%;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
      }

      [part="back-button"],
      [part="forward-button"] {
        pointer-events: none;
        opacity: 0;
        cursor: default;
      }

      :host([overflow~="start"]) [part="back-button"],
      :host([overflow~="end"]) [part="forward-button"] {
        pointer-events: auto;
        opacity: 1;
      }

      [part="back-button"]::after {
        content: '◀';
      }

      [part="forward-button"]::after {
        content: '▶';
      }

      :host([orientation="vertical"]) [part="back-button"],
      :host([orientation="vertical"]) [part="forward-button"] {
        display: none;
      }
    </style>
    <div on-click="_scrollBack" part="back-button"></div>

    <div id="scroll" part="tabs">
      <slot></slot>
    </div>

    <div on-click="_scrollForward" part="forward-button"></div>
`}static get is(){return"vaadin-tabs"}static get version(){return"3.0.4"}static get properties(){return{orientation:{value:"horizontal",type:String},selected:{value:0,type:Number}}}static get observers(){return["_updateOverflow(items.*, vertical)"]}ready(){super.ready();this.addEventListener("iron-resize",()=>this._updateOverflow());this._scrollerElement.addEventListener("scroll",()=>this._updateOverflow());this.setAttribute("role","tablist");afterNextRender(this,()=>{this._updateOverflow()})}_scrollForward(){this._scroll(this._scrollOffset)}_scrollBack(){this._scroll(-1*this._scrollOffset)}get _scrollOffset(){return this._vertical?this._scrollerElement.offsetHeight:this._scrollerElement.offsetWidth}get _scrollerElement(){return this.$.scroll}_updateOverflow(){const scrollPosition=this._vertical?this._scrollerElement.scrollTop:this._scrollerElement.scrollLeft;let scrollSize=this._vertical?this._scrollerElement.scrollHeight:this._scrollerElement.scrollWidth;scrollSize-=1;let overflow=0<scrollPosition?"start":"";overflow+=scrollPosition+this._scrollOffset<scrollSize?" end":"";overflow?this.setAttribute("overflow",overflow.trim()):this.removeAttribute("overflow");this._repaintShadowNodesHack()}_repaintShadowNodesHack(){if(safari10&&this.root){const WEBKIT_PROPERTY="-webkit-backface-visibility";this.root.querySelectorAll("*").forEach(el=>{el.style[WEBKIT_PROPERTY]="visible";el.style[WEBKIT_PROPERTY]=""})}}}customElements.define(TabsElement.is,TabsElement);export{TabsElement};