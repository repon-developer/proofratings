import { createStore } from "redux";

const ACTIONS = {
    OVERALL_SAVE: "OVERALL_SAVE",

    SAVE_SETTINGS: "SAVE_SETTINGS",
    ACTIVE_SITES: "ACTIVE_SITES",
    BADGE_DISPLAY: "BADGE_DISPLAY",
    WIDGET_SQUARE: "WIDGET_SQUARE",
    WIDGET_BASIC: "WIDGET_BASIC",
    WIDGET_ICON: "WIDGET_ICON",
    WIDGET_RECTANGLE: "WIDGET_RECTANGLE",

    OVERALL_CTA_BANNER: "OVERALL_CTA_BANNER",
};

const settings = {
    current_tab: 'overview',
    activeSites: [],
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
    widget_basic: { widget_connections: null },
    widget_icon: { widget_connections: null },
    widget_rectangle: { widget_connections: null },

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


const get_active_connections = (approved = false) => {
    const connections = Object.values(proofratings.active_connections).map(item => item).sort((a,b) => b.approved - a.approved);
    if ( approved ) {
        return connections.filter(item => item.approved == true)
    }

    return connections;

}

export default store;

export { ACTIONS, get_active_connections };
