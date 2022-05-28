import { createStore } from "redux";

const ACTIONS = {
    UPDATE_SETTINGS: "UPDATE_SETTINGS",
    UPDATE_CONNECTIONS: "UPDATE_CONNECTIONS",
    UPDATE_REPORT: "UPDATE_REPORT",
};

const settings = {
    location_id: null,
    active_connections: {},
    automated_email_report: {}
};

const settingsReducer = (state = settings, action) => {
    console.log(action)
    switch (action.type) {
        case "UPDATE_SETTINGS":
            return { ...state, ...action.payload };

        case "UPDATE_CONNECTIONS":
            return { ...state, active_connections: { ...action.payload } };

        case "UPDATE_REPORT":
            return { ...state, ...action.payload };

        default:
            return state;
    }
};

const store = createStore(settingsReducer);

export default store;

export { ACTIONS };
