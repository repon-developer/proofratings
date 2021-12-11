import { createStore, combineReducers } from "redux";

const ACTIONS = {
    SAVE_SETTINGS: "SAVE_SETTINGS",
    ACTIVE_SITES: "ACTIVE_SITES",
    BADGE_DISPLAY: "BADGE_DISPLAY",
    SITES_SQUARE: "SITES_SQUARE",
    SITES_RECTANGLE: "SITES_RECTANGLE",
    OVERALL_RECTANGLE: "OVERALL_RECTANGLE",
    OVERALL_NARROW: "OVERALL_NARROW",
    OVERALL_POPUP: "OVERALL_POPUP",
    OVERALL_CTA_BANNER: "OVERALL_CTA_BANNER",
};

const settings = {
    activeSites: ["facebook"],
    badge_display: {
        sites_square: false,
        sites_rectangle: false,
        overall_cta_banner: true,
        overall_rectangle: {embed: true, float: true},
        overall_narrow: {embed: false, float: true},
    },
    sites_square: {customize: true},
    sites_rectangle: {customize: true},
    overall_rectangle: {},
    overall_narrow: {},
    overall_popup: {customize: true},
    overall_cta_banner: {
        customize: false, 
        shadow: true,
        hide_on: [3],
        button1: {
            text: 'Sign Up',
            textcolor: '#8224e3',
            shape: true
        }
    }
};

const settingsReducer = (state = settings, action) => { 
    //console.log(state, action)
    switch (action.type) {
        case "SAVE_SETTINGS":
            return action.payload;

        case "ACTIVE_SITES":
            return {...state, activeSites: action.payload};

        case "BADGE_DISPLAY":
            return {...state, badge_display: action.payload};

        case "SITES_SQUARE":
            return {...state, sites_square: {...state.sites_square, ...action.payload}};

        case "SITES_RECTANGLE":
            return {...state, sites_rectangle: {...state.sites_rectangle, ...action.payload}};

        case "OVERALL_RECTANGLE":
            return {...state, overall_rectangle: {...state.overall_rectangle, ...action.payload}};

        case "OVERALL_NARROW":
            return {...state, overall_narrow: {...state.overall_narrow, ...action.payload}};

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
