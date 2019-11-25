import"../gfs-styles/gfs-styles.js";const GfsDropdownStyle=document.createElement("template");GfsDropdownStyle.innerHTML=`
    <dom-module id="gfs-dropdown-styles">
        <template>
            <style include="gfs-styles">
                :host {
                    display: block;

                    --gfs-dropdown-border-b: 1px solid #eef1f6;
                    --gfs-dropdown-item-hover: var(--light-grey);
                    --gfs-dropdown-border-radius: 0;
                }

                :host ::slotted(*) {
                    color: #626060;
                    border-bottom: var(--gfs-dropdown-border-b);
                    width: 100%;
                    padding: 10px;
                    text-decoration: none;
                    cursor: pointer;
                    position: relative;
                    border-radius: var(--gfs-dropdown-border-radius);
                    box-sizing: border-box;
                    transition: all .3s ease-in-out;
                }

                    :host ::slotted(:last-child) {
                        margin: 0 0 5px;
                        border-bottom: none;
                    }

                    :host ::slotted(a:last-child) {
                        margin: 0 0 5px;
                        border-bottom: none;
                    }

                    :host ::slotted(*:hover) {
                        background: var(--gfs-dropdown-item-hover);
                    }
            </style>
        </template>
    </dom-module>`;document.head.appendChild(GfsDropdownStyle.content);