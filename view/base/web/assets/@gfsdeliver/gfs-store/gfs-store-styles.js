const GfsStoreStyle=document.createElement("template");GfsStoreStyle.innerHTML=`<dom-module id="gfs-store-styles">
    <template>
        <style>
            :host {
                font: normal 14px "Segoe UI", 'Helvetica Neue';
                width: 49.5%;
                margin: 0 0 8px;

                display: flex;
            }

            :host(::last-child) {
                margin-bottom: 0;
            }

            :host[orientation="horizontal"] {
                background: red;
            }

            h3 {
                margin-top: 0px;
            }

            ul {
                margin: 0;
                padding: 0;
            }

            li {
                display: block;
            }

            .hidden {
                display: none;
            }

            .fade-in {
                transition: opacity 400ms ease-in;
                z-index: 2;
                visibility: visible;
            }

            .fade-out {
                opacity: 0;
                transition: opacity 400ms ease-in;
                z-index: 0 !important;
                visibility: hidden;
            }

            .dp-card {
                margin: 0;
                padding: 10px 0;
                background-color: #fff;
                border-radius: 3px;
                border: 1px solid #dedbdb;

                position: relative;
                display: flex;
                flex-flow: row wrap;
                flex: 1 1 auto;
                justify-content: space-between;
            }

            .content {
                width: 100%;
                margin: 0;
                display: flex;
            }

            iron-icon {
                width: 16px;
                height: 16px;
            }

            .dp-heading {
                margin-top: 4px;
                margin-bottom: 4px;
            }

            .dp-small-heading, .dp-opening-hours-heading {
                margin-top: 0;
                margin-bottom: 0;
                font-size: 0.6em;
                text-align: left;
            }

                .dp-opening-hours-heading {
                    margin-top: 10px;
                }

                    .dp-opening-hours-heading:hover {
                        cursor: pointer
                    }

            .distance-ico {
                vertical-align: bottom;
                transform: rotate(35deg);
            }

            .label {
                color: white;
                font-weight: bold;
            }

            .dp-content {
                width: 100%;
            }

            .dp-address {
                text-align: left;
            }

            #dp-distance {
                font-weight: normal;
                margin-bottom: 0px;
                display: inline-block;
            }

            .dd-action {
                margin: auto 10px 0 auto;
                display: flex;
                flex: 1 0 100%;
                flex-direction: column;
                padding: 0 10px;
                box-sizing: border-box;
            }

            .choose-store {
                background: transparent;
                border: 1px solid #24b618;
                color: #24b618;
                margin: 0;
                text-align: center;
                text-decoration: none;
                cursor: pointer;
                transition: all .3s ease-in-out;
            }

                .choose-store:hover {
                    background: #edf5ec;
                }

                .choose-store.chosen, .choose-store.unchose {
                    background-color: #24b618;
                    color: #fff;
                }

                    /*.choose-store.chosen:hover {
                        opacity: .8;
                    }

                    .choose-store.unchose:hover {
                        opacity: .8;
                    }*/

            .address-wrap {
                max-width: 43%;
                margin-right: 10px;
                margin-left: auto;
                font-size: 13px;

                display: flex;
                justify-content: flex-start;
                flex-direction: column;
                flex: 0 1 auto;
            }

            .overlay #dp-address,
            .overlay .dp-small-heading,
            .overlay #dp-distance {
                display: none;
            }

                .overlay .dp-small-heading.opening-hours {
                    display: block;
                }

            .overlay .dp-small-heading {
                margin-top: 5px;
                text-align: left;
            }

            .overlay .dp-opening-hours {
                width: 100%;
                margin-top: 20px;
                padding: 5px 15px 15px;
            }

            .overlay .content {
                display: block;
            }

            .dp-card .content .dp-details {
                margin: 0;
                padding: 0 10px 5px;
                font-size: 13px;

                display: flex;
                justify-content: flex-start;
                flex-direction: column;
                flex: 1;
                overflow: hidden;
            }

            .dp-opening-hours {
                height: auto;
                margin: 5px 0 0;
                padding: 0 10px;
            }

            /* opening hours style */
            .dp-day-time-slots {
                width: auto;
                font-size: 13px;
                line-height: 10px;
                display: inline-block;
            }

            .dp-day-time-slot:nth-child(2n) {
                margin: 5px 0;
                position: relative;
            }

            li.dp-day-time-slot {
                text-align: right;
            }

            .dp-day-name {
                min-width: 75px;
                width: 30%;
                font-size: 13px;
                font-weight: bold;
                margin-top: 1px;
                text-transform: capitalize;
                vertical-align: top;
                display: inline-block;
            }

            .dp-small-heading {
                margin-bottom: 0;
                font-size: 13px;
                font-weight: bold;
            }

                .dp-small-heading.opening-hours {
                    margin: 0 0 5px;
                }

            .weekCollection {
                margin: 0 5px 10px;
            }

            .wrap-opening-hours {
                margin: 4px 0 0 0;
                min-height: 20px;
                height: auto;
                text-align: left;
            }

            .multi-times {
                line-height: 20px;
            }

            .dp-icon {
                background: #fff;
                margin: 0 0 5px;
            }

            ::content .dp-icon {
                padding: 10px 0 10px 10px;
            }

            .dp-address, .dp-distance {
                font-size: 13px;
            }

            /* clear floats */
            .clear-fl {
                clear: both;
            }

            #dp-address {
                max-width: 200px;
            }

                #dp-address .store-name, #dp-address .store-name-map {
                    margin: 0 0 3px;
                    font-weight: bold;
                    text-transform: capitalize;
                }

            #dp-address .store-name-map {
                margin-bottom: 10px;
            }

            /* map */
            #loader {
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.2);
                position: absolute;
                top: 0;
                left: 0;
                z-index: 9999;
                display: none;
                border-radius: 3px;
            }

            paper-spinner {
                --paper-spinner-stroke-width: 5px;

                width: 50px;
                height: 50px;
                position: absolute;
                top: 50%;
                left: 50%;
                margin-left: -25px;
                z-index: 100
            }

            #storeInfoWindow {
                width: 185px;
                min-height: 130px;
                padding: 0 0 0 6px;
                position: relative;
            }

            .carrier-icon img {
                width: 100px;
            }

            .store-address-wrap {
                width: 100%;
            }

            .store-address-map, .store-location-map, .store-distance {
                font-weight: 400;
                word-break: break-all;
                display: inline-block;
            }

            .store-distance {
                margin: 5px 0;
            }

            .map-btn {
                width: 100%;
                margin: 10px 0 5px;
                padding: 7px 10px;
            }

            #store_overlay, #store_overlay {
                width: 240px;
                height: 100%;
                background: rgba(0, 0, 0, .8);
                color: #f5f5f5;
                margin: auto;
                position: absolute;
                top: 0;
                left: 0;
            }

                #store_overlay_close, #store_overlay_close {
                    position: absolute;
                    top: 32px;
                    right: 32px;
                    margin-top: -28px;
                    margin-right: -20px;
                    z-index: 10;
                }

                    #store_overlay_close:hover, #store_overlay_close:hover {
                        cursor: pointer;
                    }

                    #overlay-store {
                        width: 100%;
                    }

                    .overlay {
                        font-size: 13px;
                    }

                        .overlay.dp-card {
                            background: transparent;
                            border: none;
                            padding: 0;
                        }

                        .overlay.dp-card .content .dp-details {
                            width: 100%;
                            padding: 0;
                        }

            @media (min-width: 1000px) {
                /*:host(.vertical) {
                    width: 100%;
                }*/

                :host(.vertical) .dp-card {
                    width: 100%;
                    flex: none;
                    flex-direction: column;
                    flex: 1 0 auto;
                }

                :host(.vertical) .dp-card .content .dp-details {
                    max-width: 130px;
                    padding-right: 10px;
                    padding-left: 10px;
                }

                :host(.vertical) .dp-opening-hours, :host(.vertical) .address-wrap {
                    margin-right: 10px;
                    display: block;
                }

                :host(.vertical) .dp-small-heading {
                    font-size: 13px;
                }

                :host(.vertical) .weekCollection {
                    max-width: 465px;
                    display: flex;
                    flex-direction: row;
                    flex-flow: wrap;
                    justify-content: space-between;
                }
            }

            @media (min-width: 1200px) {
                :host(.vertical) {
                    width: 100%;
                }

                :host(.vertical) .weekCollection {
                    display: block;
                }
            }

            @media (max-width: 1024px) {
                .dp-card {

                }
            }

            @media (max-width: 976px) {
                .dp-card {

                }

                .dp-card .content .dp-details {
                    /*width: 30%;*/
                }

                #dp-address {
                    margin: 0 0 10px;
                }

                .dp-opening-hours, .address-wrap {
                    /*width: 64%;*/
                    /*margin-left: initial;*/
                }

                .weekCollection {
                    /*display: flex;
                    flex-flow: row wrap;
                    justify-content: space-between;*/
                }

                .wrap-opening-hours {
                    margin: 0 5px 0 0;
                }

                    .wrap-opening-hours:nth-child(7) {
                        margin: initial;
                    }

                .dp-day-name {
                    /*min-width: 35px;*/
                    width: auto;

                }

                .dp-day-time-slots {
                    width: initial;
                    font-size: 12px;
                }
            }

            @media (max-width: 768px) /*and (orientation: landscape)*/ {
                .dp-card {

                }

                .dp-day-time-slots {
                    /*width: 65%;*/
                }

                #dp-address {
                    font-size: 12px;
                }
            }

            @media (min-width: 812px) and (max-width: 920px) {
                :host(.vertical) {
                    width: 100%;
                }

                .dp-card .content .dp-details {
                    flex: 1 0 auto;
                }

                :host(.vertical) .weekCollection {
                    display: flex;
                    flex-direction: row;
                    flex-flow: wrap;
                    justify-content: space-between;
                }

                /*:host(.vertical) .dd-action {
                    position: absolute;
                    right: 0;
                    bottom: 15px;
                }*/
            }

            @media (max-width: 700px) {
                :host {
                    width: 100%;
                    height: auto;
                    flex: 1 1 250px;
                }

                /*.dp-card .content .dp-details {
                    flex: 1 0 100%;
                }*/

                :host(.vertical) .dp-opening-hours {

                }

                .weekCollection {
                    display: flex;
                    flex-direction: row;
                    flex-flow: wrap;
                    justify-content: space-between;
                }

                :host(.vertical) .dd-action {
                    position: absolute;
                    right: 0;
                    bottom: 15px;
                }
            }

            @media (max-width: 586px) {
                :host {
                    margin: 0 20px 0 0;
                }

                .dp-card {
                    width: 100%;
                    flex: none;
                    flex-direction: column;
                    flex: 1 0 auto;
                }

                .content {
                    /*margin-bottom: 0;*/
                    /*flex-direction: column;
                    flex-flow: inherit;*/
                }

                .dp-opening-hours, .address-wrap {
                    /*width: 40%;*/
                }

                .weekCollection {
                    display: block;
                }

                .dd-action {
                    width: 100%;
                    flex: none;
                    /*position: absolute;
                    right: 0;
                    bottom: 15px;*/
                }

            }

            @media (max-width: 414px) {
                .content {
                    width: 100%;
                }

                .dp-card .content .dp-details {
                    /*width: 50%;*/
                    max-width: 130px;
                    padding-right: 10px;
                    padding-left: 10px;
                }

                .dp-opening-hours, .address-wrap {
                    /*width: 48%;*/
                    margin-right: 10px;
                    display: block;
                }

                .dp-small-heading {
                    font-size: 13px;
                }

                .weekCollection {
                    display: block;
                }
            }

            @media (max-width: 375px) {
                .dp-card {

                }

                .dp-opening-hours, .address-wrap {
                    /*width: 50%;*/
                    min-width: initial !important;
                }
            }

            @media (max-width: 340px) {

                .dp-card {

                }


                .dp-small-heading {
                    font-size: 12px;
                }

                    .dp-small-heading i {
                        font-size: 13px;
                    }

                .dp-opening-hours, .address-wrap {
                    /*width: 52%;*/
                    min-width: initial !important;
                }

                .dp-day-name {
                    width: 20%;
                }

                .dp-day-name, .dp-day-time-slots {
                    font-size: 11px;
                }
            }
         </style>
     </template>
</dom-module>`;document.head.appendChild(GfsStoreStyle.content);