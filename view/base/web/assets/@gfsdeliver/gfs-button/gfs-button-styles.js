import"../gfs-styles/gfs-styles.js";const GfsButton=document.createElement("template");GfsButton.innerHTML=`
    <dom-module id="gfs-button-styles">
        <template>
            <style include="gfs-styles">
                :host {
                    --gfs-button-color: var(--white-color);
                    --gfs-button-bg: var(--gfs-primary-color);
                    --gfs-button-margin: 0 5px 10px 0;
                    --gfs-button-padding: 10px 12px;
                    --gfs-button-border-radius: 3px;
                    --gfs-iron-icon-width: 18px;
                    --gfs-iron-icon-height: 18px;
                    --gfs-iron-icon-fill: currentcolor;
                    --gfs-iron-icon-stroke: none;
                    --gfs-button-font-size: 14px;
                    --gfs-button-font-family: "Segoe UI", 'Helvetica Neue';
                    --gfs-button-font-weight: 500;
                    --gfs-button-text-transform: normal;
                    --gfs-stripout-color: #555;

                    --gfs-text-default-hover-background: rgba(149, 145, 145, .23);
                    --gfs-text-primary-hover-background: rgba(214, 0, 0, .10);
                    --gfs-text-secondary-hover-background: rgba(51, 117, 224, .25);
                    --gfs-text-success-hover-background: rgba(6, 163, 33, 1);
                    --gfs-text-disabled-hover-background: rgba(168, 168, 168, .3);

                    --gfs-outline-default-border: 1px solid rgba(149, 145, 145, 1);
                    --gfs-outline-primary-border: 1px solid rgba(214, 0, 0, 1);
                    --gfs-outline-secondary-border: 1px solid rgba(51, 117, 224, 1);
                    --gfs-outline-success-border: rgba(6, 163, 33, 1);
                    --gfs-outline-disabled-border: 1px solid rgba(168, 168, 168, 1);

                    --gfs-outline-default-hover-background: rgba(149, 145, 145, .23);
                    --gfs-outline-primary-hover-background: rgba(214, 0, 0, .10);
                    --gfs-outline-secondary-hover-background: rgba(51, 117, 224, .25);
                    --gfs-outline-success-hover-background: rgba(6, 163, 33, .3);
                    --gfs-outline-disabled-hover-background: rgba(168, 168, 168, .3);


                    --default-button: {
                        color: var(--black-color);
                        background: var(--gfs-defaut-color);
                    };

                    --primary-button: {
                        color: var(--white-color);
                        background: var(--gfs-primary-color);
                    };

                    --secondary-button: {
                        color: var(--white-color);
                        background: var(--gfs-secondary-color);
                    };

                    --success-button: {
                        color: var(--white-color);
                        background: var(--gfs-success-color);
                    };

                    --shadow-elevation-2dp: {
                        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14),
                                    0 1px 5px 0 rgba(0, 0, 0, 0.12),
                                    0 3px 1px -2px rgba(0, 0, 0, 0.2);
                    };

                    --shadow-elevation-4dp: {
                        box-shadow: 0 4px 5px 0 rgba(0, 0, 0, 0.14),
                                    0 1px 10px 0 rgba(0, 0, 0, 0.12),
                                    0 2px 4px -1px rgba(0, 0, 0, 0.4);
                    };

                    --shadow-elevation-6dp: {
                        box-shadow: 0 6px 10px 0 rgba(0, 0, 0, 0.14),
                                    0 1px 18px 0 rgba(0, 0, 0, 0.12),
                                    0 3px 5px -1px rgba(0, 0, 0, 0.4);
                    };

                    --shadow-elevation-8dp: {
                        box-shadow: 0 8px 10px 1px rgba(0, 0, 0, 0.14),
                                    0 3px 14px 2px rgba(0, 0, 0, 0.12),
                                    0 5px 5px -3px rgba(0, 0, 0, 0.4);
                    };

                    --shadow-elevation-16dp: {
                        box-shadow: 0 16px 24px 2px rgba(0, 0, 0, 0.14),
                                    0  6px 30px 5px rgba(0, 0, 0, 0.12),
                                    0  8px 10px -5px rgba(0, 0, 0, 0.4);
                    };

                    color: var(--gfs-button-color);
                    background: var(--gfs-button-bg);
                    font-size: var(--gfs-button-font-size);
                    font-family: var(--gfs-button-font-family);
                    font-weight: var(--gfs-button-font-weight);
                    text-transform: var(--gfs-button-text-transform);
                    margin: var(--gfs-button-margin);
                    padding: var(--gfs-button-padding);
                    display: inline-block;
                    position: relative;
                    outline-width: 0;
                    border-radius: var(--gfs-button-border-radius);
                    box-sizing: border-box;

                    --paper-button: {
                        margin: 0;
                        border-radius: var(--gfs-button-border-radius);
                    };
                }

                    ::slotted(iron-icon) {
                        width: var(--gfs-iron-icon-width);
                        height: var(--gfs-iron-icon-height);
                        margin: 0 4px 0 0;
                    }

                    :host(:hover) {
                        cursor: pointer;
                    }

                    :host([raised].keyboard-focus) {
                        font-weight: bold;
                        @apply --paper-button-raised-keyboard-focus;
                    }

                    :host(:not([raised]).keyboard-focus) {
                        font-weight: bold;
                        @apply --paper-button-flat-keyboard-focus;
                    }

                    :host([disabled]) {
                        background: #eaeaea;
                        color: #a8a8a8;
                        cursor: auto;
                        pointer-events: none;

                        @apply --gfs-button-disabled;
                    }

                    :host([animated]) {
                        @apply --shadow-transition;
                    }

                    :host([elevation="1"]) {
                        @apply --shadow-elevation-2dp;
                    }

                    :host([elevation="2"]) {
                        @apply --shadow-elevation-4dp;
                    }

                    :host([elevation="3"]) {
                        @apply --shadow-elevation-6dp;
                    }

                    :host([elevation="4"]) {
                        @apply --shadow-elevation-8dp;
                    }

                    :host([elevation="5"]) {
                        @apply --shadow-elevation-16dp;
                    }

                    :host(.default) {
                        @apply --default-button;
                    }

                    :host(.primary) {
                        @apply --primary-button;
                    }

                    :host(.secondary) {
                        @apply --secondary-button;
                    }

                    :host(.success) {
                        @apply --success-button;
                    }

                    :host([text]) {
                        background: none;
                        color: var(--gfs-stripout-color);
                        transition: all .3s ease-in-out;
                    }

                        :host(.default[text]) {
                            color: var(--gfs-default-color);
                        }

                        :host(.primary[text]) {
                            color: var(--gfs-primary-color);
                        }

                        :host(.secondary[text]) {
                            color: var(--gfs-secondary-color);
                        }

                        :host(.success[text]) {
                            color: var(--gfs-success-color);
                        }

                        :host(.disabled[text]) {
                            color: var(--gfs-disabled-color);
                        }

                            :host(.default[text]:hover) {
                                background: var(--gfs-text-default-hover-background);
                            }

                            :host(.primary[text]:hover) {
                                background: var(--gfs-text-primary-hover-background);
                            }

                            :host(.secondary[text]:hover) {
                                background: var(--gfs-text-secondary-hover-background);
                            }

                            :host(.success[text]:hover) {
                                color: var(--gfs--text-success-hover-background);
                            }

                            :host(.disabled[text]:hover) {
                                background: var(--gfs-text-disabled-hover-background);
                            }


                    :host([outlined]) {
                        background: none;
                        color: var(--gfs-stripout-color);
                        transition: all .3s ease-in-out;
                    }

                        :host(.default[outlined]) {
                            color: var(--gfs-default-color);
                            border: var(--gfs-outline-default-border);
                        }

                        :host(.primary[outlined]) {
                            color: var(--gfs-primary-color);
                            border: var(--gfs-outline-primary-border);
                        }

                        :host(.secondary[outlined]) {
                            color: var(--gfs-secondary-color);
                            border: var(--gfs-outline-secondary-border);
                        }

                        :host(.success[outlined]) {
                            color: var(--gfs-success-color);
                            border: var(--gfs-outline-success-border);
                        }

                        :host(.disabled[outlined]) {
                            color: var(--gfs-disabled-color);
                            border: var(--gfs-outline-disabled-border);
                        }

                            :host(.default[outlined]:hover) {
                                background: var(--gfs-outline-default-hover-background);
                            }

                            :host(.primary[outlined]:hover) {
                                background: var(--gfs-outline-primary-hover-background);
                            }

                            :host(.secondary[outlined]:hover) {
                                background: var(--gfs-outline-secondary-hover-background);
                            }

                            :host(.disabled[outlined]:hover) {
                                background: var(--gfs-outline-disabled-hover-background);
                            }
            </style>
        </template>
    </dom-module>`;document.head.appendChild(GfsButton.content);