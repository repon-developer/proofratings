import { createStore } from "redux";

const ACTIONS = {
    OVERALL_SAVE: "OVERALL_SAVE",

    SAVE_SETTINGS: "SAVE_SETTINGS",
    ACTIVE_SITES: "ACTIVE_SITES",
    BADGE_DISPLAY: "BADGE_DISPLAY",
    SITES_SQUARE: "SITES_SQUARE",
    BADGE_BASIC: "BADGE_BASIC",
    SITES_ICON: "SITES_ICON",
    SITES_RECTANGLE: "SITES_RECTANGLE",



    OVERALL_POPUP: "OVERALL_POPUP",
    OVERALL_CTA_BANNER: "OVERALL_CTA_BANNER",
};

const settings = {
    current_tab: 'overview',
    activeSites: [],
    badge_display: {
        sites_square: false,
        badge_basic: false,
        sites_icon: false,
        sites_rectangle: false,
        overall_cta_banner: false,
        overall_rectangle_embed: false,
        overall_rectangle_float: false,
        overall_narrow_embed: false,
        overall_narrow_float: false
    },
    sites_square: { active_connections: null },
    badge_basic: { active_connections: null },
    sites_icon: { active_connections: null },
    sites_rectangle: { active_connections: null },

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

        case "SITES_SQUARE":
            return { ...state, sites_square: { ...state.sites_square, ...action.payload } };

        case "BADGE_BASIC":
            return { ...state, badge_basic: { ...state.badge_basic, ...action.payload } };

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

export default store;

export { ACTIONS };
