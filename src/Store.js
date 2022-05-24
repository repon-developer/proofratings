import { createStore } from "redux";

const ACTIONS = {
    OVERALL_SAVE: "OVERALL_SAVE",

    SAVE_SETTINGS: "SAVE_SETTINGS",
    ACTIVE_SITES: "ACTIVE_SITES",
    BADGE_DISPLAY: "BADGE_DISPLAY",
    WIDGET_SQUARE: "WIDGET_SQUARE",
    WIDGET_BASIC: "WIDGET_BASIC",
    SITES_ICON: "SITES_ICON",
    SITES_RECTANGLE: "SITES_RECTANGLE",



    OVERALL_POPUP: "OVERALL_POPUP",
    OVERALL_CTA_BANNER: "OVERALL_CTA_BANNER",
};

const settings = {
    current_tab: 'overview',
    activeSites: [],
    badge_display: {
        widget_square: false,
        badge_basic: false,
        sites_icon: false,
        sites_rectangle: false,
        overall_cta_banner: false,
        overall_rectangle_embed: false,
        overall_rectangle_float: false,
        overall_narrow_embed: false,
        overall_narrow_float: false
    },
    widget_square: { widget_connections: null },
    widget_basic: { widget_connections: null },
    sites_icon: { widget_connections: null },
    sites_rectangle: { widget_connections: null },

    overall_rectangle_embed: {},
    overall_rectangle_float: { tablet: true, mobile: true, close_button: true },

    overall_narrow_embed: {},
    overall_narrow_float: { tablet: true, mobile: true, close_button: true },

    overall_popup: {},
    overall_cta_banner: {
        customize: false,
        shadow: true,
        tablet: true,
        mobile: true,
        close_button: true,
        hide_on: [],
        button1: {
            text: 'Sign Up'
        }
    }
};

const settingsReducer = (state = settings, action) => {
    switch (action.type) {
        case "SAVE_SETTINGS":
            return action.payload;

        case "ACTIVE_SITES":
            return { ...state, activeSites: action.payload };

        case "BADGE_DISPLAY":
            return { ...state, badge_display: action.payload };

        case "WIDGET_SQUARE":
            return { ...state, widget_square: { ...state.widget_square, ...action.payload } };

        case "WIDGET_BASIC":
            return { ...state, widget_basic: { ...state.widget_basic, ...action.payload } };

        case "SITES_ICON":
            return { ...state, sites_icon: { ...state.sites_icon, ...action.payload } };

        case "SITES_RECTANGLE":
            return { ...state, sites_rectangle: { ...state.sites_rectangle, ...action.payload } };

        case "OVERALL_SAVE":
            return { ...state, [action.payload.name]: { ...state[action.payload.name], ...action.payload.data } };

        case "OVERALL_POPUP":
            return { ...state, overall_popup: { ...state.overall_popup, ...action.payload } };

        case "OVERALL_CTA_BANNER":
            return { ...state, overall_cta_banner: { ...state.overall_cta_banner, ...action.payload } };

        default:
            return state;
    }
};

const store = createStore(settingsReducer);


const get_active_connections = (approved = false) => {
    const connections = Object.values(proofratings.active_connections).map(item => item).sort((a,b) => b.approved - a.approved);
    if ( approved ) {
        return connections.filter(item => item.approved == true)
    }

    return connections;

}

export default store;

export { ACTIONS, get_active_connections };
