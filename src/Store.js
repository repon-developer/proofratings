import { createStore } from "redux";

const ACTIONS = {
    OVERALL_SAVE: "OVERALL_SAVE",
    
    SAVE_SETTINGS: "SAVE_SETTINGS",
    ACTIVE_SITES: "ACTIVE_SITES",
    BADGE_DISPLAY: "BADGE_DISPLAY",
    SITES_SQUARE: "SITES_SQUARE",
    SITES_RECTANGLE: "SITES_RECTANGLE",
    
    

    OVERALL_POPUP: "OVERALL_POPUP",
    OVERALL_CTA_BANNER: "OVERALL_CTA_BANNER",
};

const settings = {
    current_tab: 'review-sites',
    activeSites: [],
    badge_display: {
        sites_square: false,
        sites_rectangle: false,
        overall_cta_banner: false,
        overall_rectangle_embed: false,
        overall_rectangle_float: false,
        overall_narrow_embed: false,
        overall_narrow_float: false
    },
    sites_square: {active_sites: null},
    sites_rectangle: {active_sites: null},

    overall_rectangle_embed: {},
    overall_rectangle_float: {tablet: true, mobile: true, close_button: true},

    overall_narrow_embed: {},
    overall_narrow_float: {tablet: true, mobile: true, close_button: true},

    overall_popup: {},
    overall_cta_banner: {
        customize: false, 
        shadow: true,
        tablet: true,
        mobile: true,
        close_button: true,
        hide_on: [],
        button1: {
            text: 'Sign Up',
            textcolor: '#8224e3',
            shape: true
        }
    }
};

const settingsReducer = (state = settings, action) => { 
    switch (action.type) {
        case "SAVE_SETTINGS":
            return action.payload;

        case "ACTIVE_SITES":
            if (state?.sites_square?.active_sites === null) {
                state.sites_square.active_sites = action.payload;
            }

            return {...state, activeSites: action.payload};

        case "BADGE_DISPLAY":
            return {...state, badge_display: action.payload};

        case "SITES_SQUARE":
            return {...state, sites_square: {...state.sites_square, ...action.payload}};

        case "SITES_RECTANGLE":
            return {...state, sites_rectangle: {...state.sites_rectangle, ...action.payload}};


        case "OVERALL_SAVE":
            return {...state, [action.payload.name]: {...state[action.payload.name], ...action.payload.data}};

        case "OVERALL_POPUP":
            return {...state, overall_popup: {...state.overall_popup, ...action.payload}};

        case "OVERALL_CTA_BANNER":
            return {...state, overall_cta_banner: {...state.overall_cta_banner, ...action.payload}};

        default:
            return state;
    }
};

const store = createStore(settingsReducer);

export default store;

export { ACTIONS };
