import { createStore, combineReducers } from "redux";

const ACTIONS = {
    UPDATE_STATE: "UPDATE_STATE",
    UPDATE_SETTINGS: "UPDATE_SETTINGS",
    UPDATE_CONNECTIONS: "UPDATE_CONNECTIONS",
    UPDATE_REPORT: "UPDATE_REPORT",
    UPDATE_SCHEMA: "UPDATE_SCHEMA",
    ENABLE_SCHEMA: "ENABLE_SCHEMA",
};

const settings_primary_state = {
    state: {
        active_connections: {},
        automated_email_report: {},
        enable_schema: true,
        schema: null,
    },
    settings: {
        active_connections: {},
        automated_email_report: {},
        enable_schema: true,
        schema: null,
    }
}

const stateReducer = (state = settings_primary_state.state, action) => {
    switch (action.type) {
        case "UPDATE_STATE":
            return { ...state, ...action.payload };

        default:
            return state;
    }
};

const settingsReducer = (state = settings_primary_state.settings, action) => {
    const { type, payload } = action

    switch (type) {
        case "UPDATE_SETTINGS":
            return {...state, ...action.payload };

        case "UPDATE_CONNECTIONS":
            return { ...state, active_connections: { ...payload } };

        case "UPDATE_REPORT":
            return { ...state, ...payload };

        case "UPDATE_SCHEMA":
            return { ...state, schema: payload }; 

        case "ENABLE_SCHEMA":
            return { ...state, enable_schema: payload }; 
            
            

        default:
            return state;
    }
};

const reducers = combineReducers({
    state: stateReducer,
    settings: settingsReducer
});

const store = createStore(reducers);

const update_store = (action) => {
    if ( action.type !== ACTIONS.UPDATE_SETTINGS ) {
        store.dispatch({ type: ACTIONS.UPDATE_STATE, payload: {editing: true} });    
    }

    store.dispatch(action);
}


export default store;

export { ACTIONS, update_store };
