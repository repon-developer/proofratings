import { createStore, combineReducers } from "redux";

const ACTIONS = {
    ACTIVE_SITES: "ACTIVE_SITES",
    BADGE_DISPLAY: "BADGE_DISPLAY",
    SITES_SQUARE: "SITES_SQUARE",
    SITES_RECTANGLE: "SITES_RECTANGLE",
    OVERALL_RECTANGLE: "OVERALL_RECTANGLE",
};

const settings = {
    activeSites: ["facebook"],
    badge_display: {
        sites_square: false,
        sites_rectangle: false,
        overall_rectangle: {embed: true, float: true},
        overall_narrow: {embed: false, float: false},
    },
    sites_square: {customize: true},
    sites_rectangle: {customize: true},
    overall_rectangle: {}
};

const settingsReducer = (state = settings, action) => { 
    //console.log(state, action)
    switch (action.type) {
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

        default:
            return state;
    }
};

const store = createStore(settingsReducer);

export default store;

export { ACTIONS };
