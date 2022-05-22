import { createStore } from "redux";

const ACTIONS = {
    UPDATE_SETTINGS: "UPDATE_SETTINGS",
    UPDATE_CONNECTIONS: "UPDATE_CONNECTIONS",
    SAVE_SETTINGS: "SAVE_SETTINGS",
};

const settings = {
    connection_approved: [],
    connections: {}
};

const settingsReducer = (state = settings, action) => {   
    switch (action.type) {
        case "UPDATE_SETTINGS":
            return {...state, ...action.payload };

        case "UPDATE_CONNECTIONS":
            return {...state, connections: action.payload };

        default:
            return state;
    }
};

const store = createStore(settingsReducer);

export default store;

export { ACTIONS };
