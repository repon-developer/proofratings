/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/Settings/Connections.js":
/*!*************************************!*\
  !*** ./src/Settings/Connections.js ***!
  \*************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": () => (__WEBPACK_DEFAULT_EXPORT__)\n/* harmony export */ });\nconst {\n  useState,\n  useEffect\n} = React;\n\nconst SiteConnections = props => {\n  const [state, setState] = useState({\n    search: ''\n  });\n  useEffect(() => {}, []);\n\n  const isUrl = string => {\n    try {\n      return Boolean(new URL(string));\n    } catch (e) {\n      return false;\n    }\n  };\n\n  const review_sites = [];\n\n  if (typeof proofratings?.review_sites === 'object') {\n    Object.entries(proofratings.review_sites).forEach(item => {\n      item[1].slug = item[0];\n      review_sites.push(item[1]);\n    });\n  }\n\n  const get_row = review_site => {\n    return /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement(\"td\", null, /*#__PURE__*/React.createElement(\"input\", {\n      className: \"checkbox-switch checkbox-onoff\",\n      type: \"checkbox\"\n    })), /*#__PURE__*/React.createElement(\"td\", {\n      className: \"review-site-logo\"\n    }, /*#__PURE__*/React.createElement(\"img\", {\n      src: review_site.logo,\n      alt: review_site.name\n    })), /*#__PURE__*/React.createElement(\"td\", {\n      className: \"bold\"\n    }, \"55\"), /*#__PURE__*/React.createElement(\"td\", {\n      className: \"bold\"\n    }, \"4\"), /*#__PURE__*/React.createElement(\"td\", {\n      className: \"click-through-url\"\n    }, /*#__PURE__*/React.createElement(\"input\", {\n      type: \"text\",\n      defaultValue: \"\"\n    }), isUrl('https://thispointer.com/javascript-check-if-string-is-url/') ? /*#__PURE__*/React.createElement(\"a\", {\n      className: \"fa-solid fa-up-right-from-square\",\n      href: \"sfsf\",\n      target: \"_blank\"\n    }) : ''));\n  };\n\n  const review_sites_filtered = review_sites.filter(item => {\n    if (!state.search.length) {\n      return true;\n    }\n\n    if (typeof item.name === 'undefined') {\n      return false;\n    }\n\n    return item.name.toLowerCase().match(new RegExp(state.search));\n  });\n  return /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement(\"div\", {\n    className: \"search-review-sites-wrapper\"\n  }, /*#__PURE__*/React.createElement(\"form\", {\n    className: \"form-search-review-sites\",\n    style: {\n      alignSelf: 'flex-end'\n    }\n  }, /*#__PURE__*/React.createElement(\"input\", {\n    type: \"text\",\n    placeholder: \"Search...\",\n    onChange: e => setState({ ...state,\n      search: e.target.value\n    })\n  }), /*#__PURE__*/React.createElement(\"button\", null)), /*#__PURE__*/React.createElement(\"div\", {\n    className: \"intro-text\"\n  }, /*#__PURE__*/React.createElement(\"h3\", null, \"Connect review sites\"), /*#__PURE__*/React.createElement(\"p\", null, \"Below is the current list of supported review sites to connect for your rating badges. During the initial setup of your account, we will connect the review sites your requested at sign up.\"), /*#__PURE__*/React.createElement(\"p\", null, \"If you would like to add additional sites, simply toggle on the review site and our support team will be notified to make the connection on the backend.\"), /*#__PURE__*/React.createElement(\"p\", null, \"Once connected, you can include the new review site in badges by accessing your Rating Badges tab.\"), /*#__PURE__*/React.createElement(\"p\", null, \"You can edit the click-through URL in this area if you would like your website visitors to visit a different link when they click the pertaining badge.\"))), /*#__PURE__*/React.createElement(\"div\", {\n    className: \"gap-50\"\n  }), /*#__PURE__*/React.createElement(\"table\", {\n    className: \"table-review-sites\"\n  }, /*#__PURE__*/React.createElement(\"thead\", null, /*#__PURE__*/React.createElement(\"tr\", null, /*#__PURE__*/React.createElement(\"th\", null), /*#__PURE__*/React.createElement(\"th\", {\n    className: \"column-review-sites\"\n  }, \"Review Site\"), /*#__PURE__*/React.createElement(\"th\", null, \"Rating\"), /*#__PURE__*/React.createElement(\"th\", null, \"# of Reviews\"), /*#__PURE__*/React.createElement(\"th\", null, \"Click-through URL\"))), /*#__PURE__*/React.createElement(\"tbody\", null, review_sites_filtered.map(review_site => /*#__PURE__*/React.createElement(\"tr\", {\n    key: review_site.slug\n  }, get_row(review_site))))));\n};\n\n/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (SiteConnections);\n\n//# sourceURL=webpack://proofratings/./src/Settings/Connections.js?");

/***/ }),

/***/ "./src/Settings/index.js":
/*!*******************************!*\
  !*** ./src/Settings/index.js ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _Connections__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Connections */ \"./src/Settings/Connections.js\");\nconst {\n  useEffect,\n  useState\n} = React;\n\n\nconst ProofratingsSettings = () => {\n  const [state, setState] = useState({\n    error: null,\n    loading: true,\n    saving: false,\n    current_tab: 'connections'\n  });\n  const [settings, setSettings] = useState({});\n  useEffect(() => {\n    const request = jQuery.post(proofratings.ajaxurl, {\n      action: 'proofratings_get_settings'\n    }, function (response) {\n      console.log(response);\n\n      if (response?.success == false) {\n        return setState({ ...state,\n          error: true,\n          loading: false\n        });\n      }\n\n      setState({ ...state,\n        error: false,\n        loading: false\n      });\n      setSettings({ ...settings\n      });\n    });\n    request.fail(function () {\n      return setState({ ...state,\n        error: true,\n        loading: false\n      });\n    });\n  }, []);\n\n  const setTab = (current_tab, e) => {\n    e.preventDefault();\n    setState({ ...state,\n      current_tab\n    });\n  };\n\n  const save_data = () => {\n    if (state.saving) {\n      return;\n    }\n\n    setState({ ...state,\n      saving: true\n    });\n    settings.action = 'proofratings_save_location';\n    settings.location_id = location_id;\n    jQuery.post(proofratings.ajaxurl, settings, function (response) {\n      if (response?.success == false) {\n        alert('Something wrong with saving data');\n      }\n\n      setState({ ...state,\n        saving: false\n      });\n    });\n  };\n\n  if (state.loading === true) {\n    return /*#__PURE__*/React.createElement(\"div\", {\n      className: \"proofraing-progress-msg\"\n    }, \"Loading...\");\n  }\n\n  if (state.error === true) {\n    return /*#__PURE__*/React.createElement(\"div\", {\n      className: \"proofraing-progress-msg\"\n    }, \"Failed to retrive this location.\");\n  }\n\n  const tabs = {\n    'connections': 'Site Connections',\n    'report': 'Monthly Report',\n    'schema': 'Schema'\n  };\n  const current_tab = state?.current_tab || 'badge-overview';\n  return /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement(\"header\", {\n    className: \"proofratins-header\"\n  }, /*#__PURE__*/React.createElement(\"a\", {\n    className: \"btn-back-main-menu\",\n    href: \"/wp-admin/admin.php?page=proofratings\"\n  }, /*#__PURE__*/React.createElement(\"i\", {\n    className: \"icon-back fa-solid fa-angle-left\"\n  }), \" Back to Main Menu\"), /*#__PURE__*/React.createElement(\"h1\", {\n    className: \"title\"\n  }, \"Settings\"), /*#__PURE__*/React.createElement(\"div\", {\n    className: \"rating-badges-navtab\"\n  }, Object.keys(tabs).map(key => {\n    const tab_class = current_tab === key ? 'active' : '';\n    return /*#__PURE__*/React.createElement(\"a\", {\n      key: key,\n      href: \"#\",\n      onClick: e => setTab(key, e),\n      className: tab_class\n    }, tabs[key]);\n  }))), current_tab === 'connections' && /*#__PURE__*/React.createElement(_Connections__WEBPACK_IMPORTED_MODULE_0__[\"default\"], null), current_tab === 'report' && /*#__PURE__*/React.createElement(_Connections__WEBPACK_IMPORTED_MODULE_0__[\"default\"], null), current_tab === 'schema' && /*#__PURE__*/React.createElement(_Connections__WEBPACK_IMPORTED_MODULE_0__[\"default\"], null), /*#__PURE__*/React.createElement(\"div\", {\n    className: \"form-footer\"\n  }, /*#__PURE__*/React.createElement(\"button\", {\n    className: \"button button-primary btn-save\",\n    onClick: save_data\n  }, state.saving ? 'Saving...' : 'SAVE CHANGE'), /*#__PURE__*/React.createElement(\"a\", {\n    className: \"btn-cancel\",\n    href: \"/wp-admin/admin.php?page=proofratings\"\n  }, \"CANCEL\")));\n};\n\nconst proofratings_settings_root = document.getElementById(\"proofratings-settings-root\");\n\nif (proofratings_settings_root) {\n  ReactDOM.render( /*#__PURE__*/React.createElement(ProofratingsSettings, null), proofratings_settings_root);\n}\n\n//# sourceURL=webpack://proofratings/./src/Settings/index.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = __webpack_require__("./src/Settings/index.js");
/******/ 	
/******/ })()
;