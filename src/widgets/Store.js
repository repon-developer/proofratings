import { createStore } from "redux";

import { get_proofratings } from "../global";

const ACTIONS = {
    OVERALL_SAVE: "OVERALL_SAVE",

    UPDATE_SETTINGS: "UPDATE_SETTINGS",
    ACTIVE_SITES: "ACTIVE_SITES",
    BADGE_DISPLAY: "BADGE_DISPLAY",
    WIDGET_SQUARE: "WIDGET_SQUARE",
    WIDGET_BASIC: "WIDGET_BASIC",
    WIDGET_ICON: "WIDGET_ICON",
    WIDGET_RECTANGLE: "WIDGET_RECTANGLE",

    OVERALL_CTA_BANNER: "OVERALL_CTA_BANNER",
};



const widget_primary_state = {
    current_tab: 'overview',
    //overview_summary_tab: 'embedded-badges',
    active_connections: [],
    badge_display: {
        widget_square: false,
        widget_basic: false,
        widget_icon: false,
        widget_rectangle: false,
        overall_cta_banner: false,
        overall_rectangle_embed: false,
        overall_rectangle_float: false,
        overall_narrow_embed: false,
        overall_narrow_float: false
    },
    widget_square: { widget_connections: null },
    widget_basic: { widget_connections: [] },
    widget_icon: { widget_connections: [] },
    widget_rectangle: { widget_connections: [], shadow: {shadow: true} },

    overall_rectangle_embed: {},
    overall_rectangle_float: { tablet: true, mobile: true, close_button: true },

    overall_narrow_embed: {},
    overall_narrow_float: { tablet: true, mobile: true, close_button: true },

    overall_popup: {},
    overall_cta_banner: {
        shadow: true,
        tablet: true,
        mobile: true,
        close_button_desktop: true,
        close_button_mobile: false,
        hide_on: [],
        button1: {
            show: true,
            text: 'Sign Up'
        }
    }
};

const settingsReducer = (state = widget_primary_state, action) => {
    switch (action.type) {
        case "UPDATE_SETTINGS":
            return {...state, ...action.payload};

        case "ACTIVE_SITES":
            return { ...state, activeSites: action.payload };

        case "BADGE_DISPLAY":
            return { ...state, badge_display: action.payload };

        case "WIDGET_SQUARE":
            return { ...state, widget_square: { ...state.widget_square, ...action.payload } };

        case "WIDGET_BASIC":
            return { ...state, widget_basic: { ...state.widget_basic, ...action.payload } };

        case "WIDGET_ICON":
            return { ...state, widget_icon: { ...state.widget_icon, ...action.payload } };

        case "WIDGET_RECTANGLE":
            return { ...state, widget_rectangle: { ...state.widget_rectangle, ...action.payload } };

        case "OVERALL_SAVE":
            return { ...state, [action.payload.name]: { ...state[action.payload.name], ...action.payload.data } };

        case "OVERALL_CTA_BANNER":
            return { ...state, overall_cta_banner: { ...state.overall_cta_banner, ...action.payload } };

        default:
            return state;
    }
};

const store = createStore(settingsReducer);

const get_settings = () => store.getState()

const get_connections = () => {
    const active_connections = store.getState().active_connections;
    
    let connections = Object.keys(get_proofratings().review_sites).map(key => {
        return {slug: key, approved: get_proofratings().connections_approved.includes(key),  ...get_proofratings().review_sites[key]}
    })

    
    connections = connections.filter(item => typeof active_connections[item.slug] === 'object' && active_connections[item.slug]?.selected === true);
    connections = connections.sort((a,b) => b.approved - a.approved);
    return connections;
}


export default store;

export { ACTIONS, get_settings, get_connections };
