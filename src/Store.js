import { createStore, combineReducers } from "redux";

const ACTIONS = {
    ACTIVE_SITES: "ACTIVE_SITES",
    BADGE_DISPLAY: "BADGE_DISPLAY",
};

const settings = {
    activeSites: ["facebook"],
    badge_display: {
        sites_square: true,
        sites_rectangle: false,
    },
    sites_square: {},
};

const settingsReducer = (state = settings, action) => {
    //console.log(state, action);
    
    switch (action.type) {
        case "ACTIVE_SITES":
            state.activeSites = action.payload;
            return state;

        case "BADGE_DISPLAY":
            state.badge_display = action.payload;
            return state;

        default:
            return state;
    }
};

const store = createStore(settingsReducer);

export default store;

export { ACTIONS };
