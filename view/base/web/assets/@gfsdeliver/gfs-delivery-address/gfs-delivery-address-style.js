import"../../@polymer/polymer/polymer-element.js";const GfsDeliveryAddressStyle=document.createElement("template");GfsDeliveryAddressStyle.innerHTML=`
    <dom-module id="gfs-delivery-address-style">
        <template>
            <style>
                :host {
                    font: normal 15px "Segoe UI", 'Helvetica Neue';
                    color: hsla(214, 43%, 19%, .61);
                }

                h3 {
                    margin: 0;
                    padding: 0 0 9px;
                    font-size: 23px;
                    font-weight: normal;
                    line-height: 19px;
                    position: relative;
                }

                    h3::before, h3::after {
                        content: '';
                        position: absolute;
                        top: 100%;
                        right: 0;
                        bottom: 0;
                        left: 0;
                    }

                    h3::before {
                        background: #ccc;
                        height: 1px;
                        overflow: hidden;
                    }

                    h3::after {
                        background: #1676f3;
                        width: 35px;
                        height: 1px;
                    }

                .info {
                    margin: 10px 0 0;
                }

                    .infoText {
                        margin: 10px 0 1px;
                    }

                        .infoText strong {
                            font-size: 16px;
                        }

                    .delivery-address {
                        font-size: 14px;
                    }

                    .red {
                        color: #d60000;
                        font-weight: bold;
                    }

                    .price {
                        color: #29a54f;
                    }

            </style>
        </template>
    </dom-module>
    `;document.head.appendChild(GfsDeliveryAddressStyle.content);