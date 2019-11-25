const GfsStyles=document.createElement("template");GfsStyles.innerHTML=`
    <dom-module id="gfs-styles">
        <template>
            <style>
                :host {
                    font: normal 14px "Segoe UI";

                    --white-color: #fff;
                    --black-color: rgba(0, 0, 0, 0.87);
                    --gfs-defaut-color: #e0e0e0;
                    --gfs-primary-color: #d60000;
                    --gfs-secondary-color: #3375e0;
                    --gfs-success-color: #04c526;
                    --gfs-disabled-color: #a8a8a8;
                    --gfs-third-color: #b6b6b6;
                    --light-grey: #f7f8fb;
                }
            </style>
        </template>
    </dom-module>`;document.head.appendChild(GfsStyles.content);