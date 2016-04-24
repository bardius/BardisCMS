(function(global, factory) {
    if (typeof module === "object" && typeof module.exports === "object") {
        module.exports = global.document ? factory(global, true) : function(w) {
            if (!w.document) {
                throw new Error("jQuery requires a window with a document");
            }
            return factory(w);
        };
    } else {
        factory(global);
    }
})(typeof window !== "undefined" ? window : this, function(window, noGlobal) {
    var arr = [];
    var slice = arr.slice;
    var concat = arr.concat;
    var push = arr.push;
    var indexOf = arr.indexOf;
    var class2type = {};
    var toString = class2type.toString;
    var hasOwn = class2type.hasOwnProperty;
    var support = {};
    var document = window.document, version = "2.1.4", jQuery = function(selector, context) {
        return new jQuery.fn.init(selector, context);
    }, rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, rmsPrefix = /^-ms-/, rdashAlpha = /-([\da-z])/gi, fcamelCase = function(all, letter) {
        return letter.toUpperCase();
    };
    jQuery.fn = jQuery.prototype = {
        jquery: version,
        constructor: jQuery,
        selector: "",
        length: 0,
        toArray: function() {
            return slice.call(this);
        },
        get: function(num) {
            return num != null ? num < 0 ? this[num + this.length] : this[num] : slice.call(this);
        },
        pushStack: function(elems) {
            var ret = jQuery.merge(this.constructor(), elems);
            ret.prevObject = this;
            ret.context = this.context;
            return ret;
        },
        each: function(callback, args) {
            return jQuery.each(this, callback, args);
        },
        map: function(callback) {
            return this.pushStack(jQuery.map(this, function(elem, i) {
                return callback.call(elem, i, elem);
            }));
        },
        slice: function() {
            return this.pushStack(slice.apply(this, arguments));
        },
        first: function() {
            return this.eq(0);
        },
        last: function() {
            return this.eq(-1);
        },
        eq: function(i) {
            var len = this.length, j = +i + (i < 0 ? len : 0);
            return this.pushStack(j >= 0 && j < len ? [ this[j] ] : []);
        },
        end: function() {
            return this.prevObject || this.constructor(null);
        },
        push: push,
        sort: arr.sort,
        splice: arr.splice
    };
    jQuery.extend = jQuery.fn.extend = function() {
        var options, name, src, copy, copyIsArray, clone, target = arguments[0] || {}, i = 1, length = arguments.length, deep = false;
        if (typeof target === "boolean") {
            deep = target;
            target = arguments[i] || {};
            i++;
        }
        if (typeof target !== "object" && !jQuery.isFunction(target)) {
            target = {};
        }
        if (i === length) {
            target = this;
            i--;
        }
        for (;i < length; i++) {
            if ((options = arguments[i]) != null) {
                for (name in options) {
                    src = target[name];
                    copy = options[name];
                    if (target === copy) {
                        continue;
                    }
                    if (deep && copy && (jQuery.isPlainObject(copy) || (copyIsArray = jQuery.isArray(copy)))) {
                        if (copyIsArray) {
                            copyIsArray = false;
                            clone = src && jQuery.isArray(src) ? src : [];
                        } else {
                            clone = src && jQuery.isPlainObject(src) ? src : {};
                        }
                        target[name] = jQuery.extend(deep, clone, copy);
                    } else if (copy !== undefined) {
                        target[name] = copy;
                    }
                }
            }
        }
        return target;
    };
    jQuery.extend({
        expando: "jQuery" + (version + Math.random()).replace(/\D/g, ""),
        isReady: true,
        error: function(msg) {
            throw new Error(msg);
        },
        noop: function() {},
        isFunction: function(obj) {
            return jQuery.type(obj) === "function";
        },
        isArray: Array.isArray,
        isWindow: function(obj) {
            return obj != null && obj === obj.window;
        },
        isNumeric: function(obj) {
            return !jQuery.isArray(obj) && obj - parseFloat(obj) + 1 >= 0;
        },
        isPlainObject: function(obj) {
            if (jQuery.type(obj) !== "object" || obj.nodeType || jQuery.isWindow(obj)) {
                return false;
            }
            if (obj.constructor && !hasOwn.call(obj.constructor.prototype, "isPrototypeOf")) {
                return false;
            }
            return true;
        },
        isEmptyObject: function(obj) {
            var name;
            for (name in obj) {
                return false;
            }
            return true;
        },
        type: function(obj) {
            if (obj == null) {
                return obj + "";
            }
            return typeof obj === "object" || typeof obj === "function" ? class2type[toString.call(obj)] || "object" : typeof obj;
        },
        globalEval: function(code) {
            var script, indirect = eval;
            code = jQuery.trim(code);
            if (code) {
                if (code.indexOf("use strict") === 1) {
                    script = document.createElement("script");
                    script.text = code;
                    document.head.appendChild(script).parentNode.removeChild(script);
                } else {
                    indirect(code);
                }
            }
        },
        camelCase: function(string) {
            return string.replace(rmsPrefix, "ms-").replace(rdashAlpha, fcamelCase);
        },
        nodeName: function(elem, name) {
            return elem.nodeName && elem.nodeName.toLowerCase() === name.toLowerCase();
        },
        each: function(obj, callback, args) {
            var value, i = 0, length = obj.length, isArray = isArraylike(obj);
            if (args) {
                if (isArray) {
                    for (;i < length; i++) {
                        value = callback.apply(obj[i], args);
                        if (value === false) {
                            break;
                        }
                    }
                } else {
                    for (i in obj) {
                        value = callback.apply(obj[i], args);
                        if (value === false) {
                            break;
                        }
                    }
                }
            } else {
                if (isArray) {
                    for (;i < length; i++) {
                        value = callback.call(obj[i], i, obj[i]);
                        if (value === false) {
                            break;
                        }
                    }
                } else {
                    for (i in obj) {
                        value = callback.call(obj[i], i, obj[i]);
                        if (value === false) {
                            break;
                        }
                    }
                }
            }
            return obj;
        },
        trim: function(text) {
            return text == null ? "" : (text + "").replace(rtrim, "");
        },
        makeArray: function(arr, results) {
            var ret = results || [];
            if (arr != null) {
                if (isArraylike(Object(arr))) {
                    jQuery.merge(ret, typeof arr === "string" ? [ arr ] : arr);
                } else {
                    push.call(ret, arr);
                }
            }
            return ret;
        },
        inArray: function(elem, arr, i) {
            return arr == null ? -1 : indexOf.call(arr, elem, i);
        },
        merge: function(first, second) {
            var len = +second.length, j = 0, i = first.length;
            for (;j < len; j++) {
                first[i++] = second[j];
            }
            first.length = i;
            return first;
        },
        grep: function(elems, callback, invert) {
            var callbackInverse, matches = [], i = 0, length = elems.length, callbackExpect = !invert;
            for (;i < length; i++) {
                callbackInverse = !callback(elems[i], i);
                if (callbackInverse !== callbackExpect) {
                    matches.push(elems[i]);
                }
            }
            return matches;
        },
        map: function(elems, callback, arg) {
            var value, i = 0, length = elems.length, isArray = isArraylike(elems), ret = [];
            if (isArray) {
                for (;i < length; i++) {
                    value = callback(elems[i], i, arg);
                    if (value != null) {
                        ret.push(value);
                    }
                }
            } else {
                for (i in elems) {
                    value = callback(elems[i], i, arg);
                    if (value != null) {
                        ret.push(value);
                    }
                }
            }
            return concat.apply([], ret);
        },
        guid: 1,
        proxy: function(fn, context) {
            var tmp, args, proxy;
            if (typeof context === "string") {
                tmp = fn[context];
                context = fn;
                fn = tmp;
            }
            if (!jQuery.isFunction(fn)) {
                return undefined;
            }
            args = slice.call(arguments, 2);
            proxy = function() {
                return fn.apply(context || this, args.concat(slice.call(arguments)));
            };
            proxy.guid = fn.guid = fn.guid || jQuery.guid++;
            return proxy;
        },
        now: Date.now,
        support: support
    });
    jQuery.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function(i, name) {
        class2type["[object " + name + "]"] = name.toLowerCase();
    });
    function isArraylike(obj) {
        var length = "length" in obj && obj.length, type = jQuery.type(obj);
        if (type === "function" || jQuery.isWindow(obj)) {
            return false;
        }
        if (obj.nodeType === 1 && length) {
            return true;
        }
        return type === "array" || length === 0 || typeof length === "number" && length > 0 && length - 1 in obj;
    }
    var Sizzle = function(window) {
        var i, support, Expr, getText, isXML, tokenize, compile, select, outermostContext, sortInput, hasDuplicate, setDocument, document, docElem, documentIsHTML, rbuggyQSA, rbuggyMatches, matches, contains, expando = "sizzle" + 1 * new Date(), preferredDoc = window.document, dirruns = 0, done = 0, classCache = createCache(), tokenCache = createCache(), compilerCache = createCache(), sortOrder = function(a, b) {
            if (a === b) {
                hasDuplicate = true;
            }
            return 0;
        }, MAX_NEGATIVE = 1 << 31, hasOwn = {}.hasOwnProperty, arr = [], pop = arr.pop, push_native = arr.push, push = arr.push, slice = arr.slice, indexOf = function(list, elem) {
            var i = 0, len = list.length;
            for (;i < len; i++) {
                if (list[i] === elem) {
                    return i;
                }
            }
            return -1;
        }, booleans = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped", whitespace = "[\\x20\\t\\r\\n\\f]", characterEncoding = "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+", identifier = characterEncoding.replace("w", "w#"), attributes = "\\[" + whitespace + "*(" + characterEncoding + ")(?:" + whitespace + "*([*^$|!~]?=)" + whitespace + "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + identifier + "))|)" + whitespace + "*\\]", pseudos = ":(" + characterEncoding + ")(?:\\((" + "('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|" + "((?:\\\\.|[^\\\\()[\\]]|" + attributes + ")*)|" + ".*" + ")\\)|)", rwhitespace = new RegExp(whitespace + "+", "g"), rtrim = new RegExp("^" + whitespace + "+|((?:^|[^\\\\])(?:\\\\.)*)" + whitespace + "+$", "g"), rcomma = new RegExp("^" + whitespace + "*," + whitespace + "*"), rcombinators = new RegExp("^" + whitespace + "*([>+~]|" + whitespace + ")" + whitespace + "*"), rattributeQuotes = new RegExp("=" + whitespace + "*([^\\]'\"]*?)" + whitespace + "*\\]", "g"), rpseudo = new RegExp(pseudos), ridentifier = new RegExp("^" + identifier + "$"), matchExpr = {
            ID: new RegExp("^#(" + characterEncoding + ")"),
            CLASS: new RegExp("^\\.(" + characterEncoding + ")"),
            TAG: new RegExp("^(" + characterEncoding.replace("w", "w*") + ")"),
            ATTR: new RegExp("^" + attributes),
            PSEUDO: new RegExp("^" + pseudos),
            CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + whitespace + "*(even|odd|(([+-]|)(\\d*)n|)" + whitespace + "*(?:([+-]|)" + whitespace + "*(\\d+)|))" + whitespace + "*\\)|)", "i"),
            bool: new RegExp("^(?:" + booleans + ")$", "i"),
            needsContext: new RegExp("^" + whitespace + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + whitespace + "*((?:-\\d)?\\d*)" + whitespace + "*\\)|)(?=[^-]|$)", "i")
        }, rinputs = /^(?:input|select|textarea|button)$/i, rheader = /^h\d$/i, rnative = /^[^{]+\{\s*\[native \w/, rquickExpr = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/, rsibling = /[+~]/, rescape = /'|\\/g, runescape = new RegExp("\\\\([\\da-f]{1,6}" + whitespace + "?|(" + whitespace + ")|.)", "ig"), funescape = function(_, escaped, escapedWhitespace) {
            var high = "0x" + escaped - 65536;
            return high !== high || escapedWhitespace ? escaped : high < 0 ? String.fromCharCode(high + 65536) : String.fromCharCode(high >> 10 | 55296, high & 1023 | 56320);
        }, unloadHandler = function() {
            setDocument();
        };
        try {
            push.apply(arr = slice.call(preferredDoc.childNodes), preferredDoc.childNodes);
            arr[preferredDoc.childNodes.length].nodeType;
        } catch (e) {
            push = {
                apply: arr.length ? function(target, els) {
                    push_native.apply(target, slice.call(els));
                } : function(target, els) {
                    var j = target.length, i = 0;
                    while (target[j++] = els[i++]) {}
                    target.length = j - 1;
                }
            };
        }
        function Sizzle(selector, context, results, seed) {
            var match, elem, m, nodeType, i, groups, old, nid, newContext, newSelector;
            if ((context ? context.ownerDocument || context : preferredDoc) !== document) {
                setDocument(context);
            }
            context = context || document;
            results = results || [];
            nodeType = context.nodeType;
            if (typeof selector !== "string" || !selector || nodeType !== 1 && nodeType !== 9 && nodeType !== 11) {
                return results;
            }
            if (!seed && documentIsHTML) {
                if (nodeType !== 11 && (match = rquickExpr.exec(selector))) {
                    if (m = match[1]) {
                        if (nodeType === 9) {
                            elem = context.getElementById(m);
                            if (elem && elem.parentNode) {
                                if (elem.id === m) {
                                    results.push(elem);
                                    return results;
                                }
                            } else {
                                return results;
                            }
                        } else {
                            if (context.ownerDocument && (elem = context.ownerDocument.getElementById(m)) && contains(context, elem) && elem.id === m) {
                                results.push(elem);
                                return results;
                            }
                        }
                    } else if (match[2]) {
                        push.apply(results, context.getElementsByTagName(selector));
                        return results;
                    } else if ((m = match[3]) && support.getElementsByClassName) {
                        push.apply(results, context.getElementsByClassName(m));
                        return results;
                    }
                }
                if (support.qsa && (!rbuggyQSA || !rbuggyQSA.test(selector))) {
                    nid = old = expando;
                    newContext = context;
                    newSelector = nodeType !== 1 && selector;
                    if (nodeType === 1 && context.nodeName.toLowerCase() !== "object") {
                        groups = tokenize(selector);
                        if (old = context.getAttribute("id")) {
                            nid = old.replace(rescape, "\\$&");
                        } else {
                            context.setAttribute("id", nid);
                        }
                        nid = "[id='" + nid + "'] ";
                        i = groups.length;
                        while (i--) {
                            groups[i] = nid + toSelector(groups[i]);
                        }
                        newContext = rsibling.test(selector) && testContext(context.parentNode) || context;
                        newSelector = groups.join(",");
                    }
                    if (newSelector) {
                        try {
                            push.apply(results, newContext.querySelectorAll(newSelector));
                            return results;
                        } catch (qsaError) {} finally {
                            if (!old) {
                                context.removeAttribute("id");
                            }
                        }
                    }
                }
            }
            return select(selector.replace(rtrim, "$1"), context, results, seed);
        }
        function createCache() {
            var keys = [];
            function cache(key, value) {
                if (keys.push(key + " ") > Expr.cacheLength) {
                    delete cache[keys.shift()];
                }
                return cache[key + " "] = value;
            }
            return cache;
        }
        function markFunction(fn) {
            fn[expando] = true;
            return fn;
        }
        function assert(fn) {
            var div = document.createElement("div");
            try {
                return !!fn(div);
            } catch (e) {
                return false;
            } finally {
                if (div.parentNode) {
                    div.parentNode.removeChild(div);
                }
                div = null;
            }
        }
        function addHandle(attrs, handler) {
            var arr = attrs.split("|"), i = attrs.length;
            while (i--) {
                Expr.attrHandle[arr[i]] = handler;
            }
        }
        function siblingCheck(a, b) {
            var cur = b && a, diff = cur && a.nodeType === 1 && b.nodeType === 1 && (~b.sourceIndex || MAX_NEGATIVE) - (~a.sourceIndex || MAX_NEGATIVE);
            if (diff) {
                return diff;
            }
            if (cur) {
                while (cur = cur.nextSibling) {
                    if (cur === b) {
                        return -1;
                    }
                }
            }
            return a ? 1 : -1;
        }
        function createInputPseudo(type) {
            return function(elem) {
                var name = elem.nodeName.toLowerCase();
                return name === "input" && elem.type === type;
            };
        }
        function createButtonPseudo(type) {
            return function(elem) {
                var name = elem.nodeName.toLowerCase();
                return (name === "input" || name === "button") && elem.type === type;
            };
        }
        function createPositionalPseudo(fn) {
            return markFunction(function(argument) {
                argument = +argument;
                return markFunction(function(seed, matches) {
                    var j, matchIndexes = fn([], seed.length, argument), i = matchIndexes.length;
                    while (i--) {
                        if (seed[j = matchIndexes[i]]) {
                            seed[j] = !(matches[j] = seed[j]);
                        }
                    }
                });
            });
        }
        function testContext(context) {
            return context && typeof context.getElementsByTagName !== "undefined" && context;
        }
        support = Sizzle.support = {};
        isXML = Sizzle.isXML = function(elem) {
            var documentElement = elem && (elem.ownerDocument || elem).documentElement;
            return documentElement ? documentElement.nodeName !== "HTML" : false;
        };
        setDocument = Sizzle.setDocument = function(node) {
            var hasCompare, parent, doc = node ? node.ownerDocument || node : preferredDoc;
            if (doc === document || doc.nodeType !== 9 || !doc.documentElement) {
                return document;
            }
            document = doc;
            docElem = doc.documentElement;
            parent = doc.defaultView;
            if (parent && parent !== parent.top) {
                if (parent.addEventListener) {
                    parent.addEventListener("unload", unloadHandler, false);
                } else if (parent.attachEvent) {
                    parent.attachEvent("onunload", unloadHandler);
                }
            }
            documentIsHTML = !isXML(doc);
            support.attributes = assert(function(div) {
                div.className = "i";
                return !div.getAttribute("className");
            });
            support.getElementsByTagName = assert(function(div) {
                div.appendChild(doc.createComment(""));
                return !div.getElementsByTagName("*").length;
            });
            support.getElementsByClassName = rnative.test(doc.getElementsByClassName);
            support.getById = assert(function(div) {
                docElem.appendChild(div).id = expando;
                return !doc.getElementsByName || !doc.getElementsByName(expando).length;
            });
            if (support.getById) {
                Expr.find["ID"] = function(id, context) {
                    if (typeof context.getElementById !== "undefined" && documentIsHTML) {
                        var m = context.getElementById(id);
                        return m && m.parentNode ? [ m ] : [];
                    }
                };
                Expr.filter["ID"] = function(id) {
                    var attrId = id.replace(runescape, funescape);
                    return function(elem) {
                        return elem.getAttribute("id") === attrId;
                    };
                };
            } else {
                delete Expr.find["ID"];
                Expr.filter["ID"] = function(id) {
                    var attrId = id.replace(runescape, funescape);
                    return function(elem) {
                        var node = typeof elem.getAttributeNode !== "undefined" && elem.getAttributeNode("id");
                        return node && node.value === attrId;
                    };
                };
            }
            Expr.find["TAG"] = support.getElementsByTagName ? function(tag, context) {
                if (typeof context.getElementsByTagName !== "undefined") {
                    return context.getElementsByTagName(tag);
                } else if (support.qsa) {
                    return context.querySelectorAll(tag);
                }
            } : function(tag, context) {
                var elem, tmp = [], i = 0, results = context.getElementsByTagName(tag);
                if (tag === "*") {
                    while (elem = results[i++]) {
                        if (elem.nodeType === 1) {
                            tmp.push(elem);
                        }
                    }
                    return tmp;
                }
                return results;
            };
            Expr.find["CLASS"] = support.getElementsByClassName && function(className, context) {
                if (documentIsHTML) {
                    return context.getElementsByClassName(className);
                }
            };
            rbuggyMatches = [];
            rbuggyQSA = [];
            if (support.qsa = rnative.test(doc.querySelectorAll)) {
                assert(function(div) {
                    docElem.appendChild(div).innerHTML = "<a id='" + expando + "'></a>" + "<select id='" + expando + "-\f]' msallowcapture=''>" + "<option selected=''></option></select>";
                    if (div.querySelectorAll("[msallowcapture^='']").length) {
                        rbuggyQSA.push("[*^$]=" + whitespace + "*(?:''|\"\")");
                    }
                    if (!div.querySelectorAll("[selected]").length) {
                        rbuggyQSA.push("\\[" + whitespace + "*(?:value|" + booleans + ")");
                    }
                    if (!div.querySelectorAll("[id~=" + expando + "-]").length) {
                        rbuggyQSA.push("~=");
                    }
                    if (!div.querySelectorAll(":checked").length) {
                        rbuggyQSA.push(":checked");
                    }
                    if (!div.querySelectorAll("a#" + expando + "+*").length) {
                        rbuggyQSA.push(".#.+[+~]");
                    }
                });
                assert(function(div) {
                    var input = doc.createElement("input");
                    input.setAttribute("type", "hidden");
                    div.appendChild(input).setAttribute("name", "D");
                    if (div.querySelectorAll("[name=d]").length) {
                        rbuggyQSA.push("name" + whitespace + "*[*^$|!~]?=");
                    }
                    if (!div.querySelectorAll(":enabled").length) {
                        rbuggyQSA.push(":enabled", ":disabled");
                    }
                    div.querySelectorAll("*,:x");
                    rbuggyQSA.push(",.*:");
                });
            }
            if (support.matchesSelector = rnative.test(matches = docElem.matches || docElem.webkitMatchesSelector || docElem.mozMatchesSelector || docElem.oMatchesSelector || docElem.msMatchesSelector)) {
                assert(function(div) {
                    support.disconnectedMatch = matches.call(div, "div");
                    matches.call(div, "[s!='']:x");
                    rbuggyMatches.push("!=", pseudos);
                });
            }
            rbuggyQSA = rbuggyQSA.length && new RegExp(rbuggyQSA.join("|"));
            rbuggyMatches = rbuggyMatches.length && new RegExp(rbuggyMatches.join("|"));
            hasCompare = rnative.test(docElem.compareDocumentPosition);
            contains = hasCompare || rnative.test(docElem.contains) ? function(a, b) {
                var adown = a.nodeType === 9 ? a.documentElement : a, bup = b && b.parentNode;
                return a === bup || !!(bup && bup.nodeType === 1 && (adown.contains ? adown.contains(bup) : a.compareDocumentPosition && a.compareDocumentPosition(bup) & 16));
            } : function(a, b) {
                if (b) {
                    while (b = b.parentNode) {
                        if (b === a) {
                            return true;
                        }
                    }
                }
                return false;
            };
            sortOrder = hasCompare ? function(a, b) {
                if (a === b) {
                    hasDuplicate = true;
                    return 0;
                }
                var compare = !a.compareDocumentPosition - !b.compareDocumentPosition;
                if (compare) {
                    return compare;
                }
                compare = (a.ownerDocument || a) === (b.ownerDocument || b) ? a.compareDocumentPosition(b) : 1;
                if (compare & 1 || !support.sortDetached && b.compareDocumentPosition(a) === compare) {
                    if (a === doc || a.ownerDocument === preferredDoc && contains(preferredDoc, a)) {
                        return -1;
                    }
                    if (b === doc || b.ownerDocument === preferredDoc && contains(preferredDoc, b)) {
                        return 1;
                    }
                    return sortInput ? indexOf(sortInput, a) - indexOf(sortInput, b) : 0;
                }
                return compare & 4 ? -1 : 1;
            } : function(a, b) {
                if (a === b) {
                    hasDuplicate = true;
                    return 0;
                }
                var cur, i = 0, aup = a.parentNode, bup = b.parentNode, ap = [ a ], bp = [ b ];
                if (!aup || !bup) {
                    return a === doc ? -1 : b === doc ? 1 : aup ? -1 : bup ? 1 : sortInput ? indexOf(sortInput, a) - indexOf(sortInput, b) : 0;
                } else if (aup === bup) {
                    return siblingCheck(a, b);
                }
                cur = a;
                while (cur = cur.parentNode) {
                    ap.unshift(cur);
                }
                cur = b;
                while (cur = cur.parentNode) {
                    bp.unshift(cur);
                }
                while (ap[i] === bp[i]) {
                    i++;
                }
                return i ? siblingCheck(ap[i], bp[i]) : ap[i] === preferredDoc ? -1 : bp[i] === preferredDoc ? 1 : 0;
            };
            return doc;
        };
        Sizzle.matches = function(expr, elements) {
            return Sizzle(expr, null, null, elements);
        };
        Sizzle.matchesSelector = function(elem, expr) {
            if ((elem.ownerDocument || elem) !== document) {
                setDocument(elem);
            }
            expr = expr.replace(rattributeQuotes, "='$1']");
            if (support.matchesSelector && documentIsHTML && (!rbuggyMatches || !rbuggyMatches.test(expr)) && (!rbuggyQSA || !rbuggyQSA.test(expr))) {
                try {
                    var ret = matches.call(elem, expr);
                    if (ret || support.disconnectedMatch || elem.document && elem.document.nodeType !== 11) {
                        return ret;
                    }
                } catch (e) {}
            }
            return Sizzle(expr, document, null, [ elem ]).length > 0;
        };
        Sizzle.contains = function(context, elem) {
            if ((context.ownerDocument || context) !== document) {
                setDocument(context);
            }
            return contains(context, elem);
        };
        Sizzle.attr = function(elem, name) {
            if ((elem.ownerDocument || elem) !== document) {
                setDocument(elem);
            }
            var fn = Expr.attrHandle[name.toLowerCase()], val = fn && hasOwn.call(Expr.attrHandle, name.toLowerCase()) ? fn(elem, name, !documentIsHTML) : undefined;
            return val !== undefined ? val : support.attributes || !documentIsHTML ? elem.getAttribute(name) : (val = elem.getAttributeNode(name)) && val.specified ? val.value : null;
        };
        Sizzle.error = function(msg) {
            throw new Error("Syntax error, unrecognized expression: " + msg);
        };
        Sizzle.uniqueSort = function(results) {
            var elem, duplicates = [], j = 0, i = 0;
            hasDuplicate = !support.detectDuplicates;
            sortInput = !support.sortStable && results.slice(0);
            results.sort(sortOrder);
            if (hasDuplicate) {
                while (elem = results[i++]) {
                    if (elem === results[i]) {
                        j = duplicates.push(i);
                    }
                }
                while (j--) {
                    results.splice(duplicates[j], 1);
                }
            }
            sortInput = null;
            return results;
        };
        getText = Sizzle.getText = function(elem) {
            var node, ret = "", i = 0, nodeType = elem.nodeType;
            if (!nodeType) {
                while (node = elem[i++]) {
                    ret += getText(node);
                }
            } else if (nodeType === 1 || nodeType === 9 || nodeType === 11) {
                if (typeof elem.textContent === "string") {
                    return elem.textContent;
                } else {
                    for (elem = elem.firstChild; elem; elem = elem.nextSibling) {
                        ret += getText(elem);
                    }
                }
            } else if (nodeType === 3 || nodeType === 4) {
                return elem.nodeValue;
            }
            return ret;
        };
        Expr = Sizzle.selectors = {
            cacheLength: 50,
            createPseudo: markFunction,
            match: matchExpr,
            attrHandle: {},
            find: {},
            relative: {
                ">": {
                    dir: "parentNode",
                    first: true
                },
                " ": {
                    dir: "parentNode"
                },
                "+": {
                    dir: "previousSibling",
                    first: true
                },
                "~": {
                    dir: "previousSibling"
                }
            },
            preFilter: {
                ATTR: function(match) {
                    match[1] = match[1].replace(runescape, funescape);
                    match[3] = (match[3] || match[4] || match[5] || "").replace(runescape, funescape);
                    if (match[2] === "~=") {
                        match[3] = " " + match[3] + " ";
                    }
                    return match.slice(0, 4);
                },
                CHILD: function(match) {
                    match[1] = match[1].toLowerCase();
                    if (match[1].slice(0, 3) === "nth") {
                        if (!match[3]) {
                            Sizzle.error(match[0]);
                        }
                        match[4] = +(match[4] ? match[5] + (match[6] || 1) : 2 * (match[3] === "even" || match[3] === "odd"));
                        match[5] = +(match[7] + match[8] || match[3] === "odd");
                    } else if (match[3]) {
                        Sizzle.error(match[0]);
                    }
                    return match;
                },
                PSEUDO: function(match) {
                    var excess, unquoted = !match[6] && match[2];
                    if (matchExpr["CHILD"].test(match[0])) {
                        return null;
                    }
                    if (match[3]) {
                        match[2] = match[4] || match[5] || "";
                    } else if (unquoted && rpseudo.test(unquoted) && (excess = tokenize(unquoted, true)) && (excess = unquoted.indexOf(")", unquoted.length - excess) - unquoted.length)) {
                        match[0] = match[0].slice(0, excess);
                        match[2] = unquoted.slice(0, excess);
                    }
                    return match.slice(0, 3);
                }
            },
            filter: {
                TAG: function(nodeNameSelector) {
                    var nodeName = nodeNameSelector.replace(runescape, funescape).toLowerCase();
                    return nodeNameSelector === "*" ? function() {
                        return true;
                    } : function(elem) {
                        return elem.nodeName && elem.nodeName.toLowerCase() === nodeName;
                    };
                },
                CLASS: function(className) {
                    var pattern = classCache[className + " "];
                    return pattern || (pattern = new RegExp("(^|" + whitespace + ")" + className + "(" + whitespace + "|$)")) && classCache(className, function(elem) {
                        return pattern.test(typeof elem.className === "string" && elem.className || typeof elem.getAttribute !== "undefined" && elem.getAttribute("class") || "");
                    });
                },
                ATTR: function(name, operator, check) {
                    return function(elem) {
                        var result = Sizzle.attr(elem, name);
                        if (result == null) {
                            return operator === "!=";
                        }
                        if (!operator) {
                            return true;
                        }
                        result += "";
                        return operator === "=" ? result === check : operator === "!=" ? result !== check : operator === "^=" ? check && result.indexOf(check) === 0 : operator === "*=" ? check && result.indexOf(check) > -1 : operator === "$=" ? check && result.slice(-check.length) === check : operator === "~=" ? (" " + result.replace(rwhitespace, " ") + " ").indexOf(check) > -1 : operator === "|=" ? result === check || result.slice(0, check.length + 1) === check + "-" : false;
                    };
                },
                CHILD: function(type, what, argument, first, last) {
                    var simple = type.slice(0, 3) !== "nth", forward = type.slice(-4) !== "last", ofType = what === "of-type";
                    return first === 1 && last === 0 ? function(elem) {
                        return !!elem.parentNode;
                    } : function(elem, context, xml) {
                        var cache, outerCache, node, diff, nodeIndex, start, dir = simple !== forward ? "nextSibling" : "previousSibling", parent = elem.parentNode, name = ofType && elem.nodeName.toLowerCase(), useCache = !xml && !ofType;
                        if (parent) {
                            if (simple) {
                                while (dir) {
                                    node = elem;
                                    while (node = node[dir]) {
                                        if (ofType ? node.nodeName.toLowerCase() === name : node.nodeType === 1) {
                                            return false;
                                        }
                                    }
                                    start = dir = type === "only" && !start && "nextSibling";
                                }
                                return true;
                            }
                            start = [ forward ? parent.firstChild : parent.lastChild ];
                            if (forward && useCache) {
                                outerCache = parent[expando] || (parent[expando] = {});
                                cache = outerCache[type] || [];
                                nodeIndex = cache[0] === dirruns && cache[1];
                                diff = cache[0] === dirruns && cache[2];
                                node = nodeIndex && parent.childNodes[nodeIndex];
                                while (node = ++nodeIndex && node && node[dir] || (diff = nodeIndex = 0) || start.pop()) {
                                    if (node.nodeType === 1 && ++diff && node === elem) {
                                        outerCache[type] = [ dirruns, nodeIndex, diff ];
                                        break;
                                    }
                                }
                            } else if (useCache && (cache = (elem[expando] || (elem[expando] = {}))[type]) && cache[0] === dirruns) {
                                diff = cache[1];
                            } else {
                                while (node = ++nodeIndex && node && node[dir] || (diff = nodeIndex = 0) || start.pop()) {
                                    if ((ofType ? node.nodeName.toLowerCase() === name : node.nodeType === 1) && ++diff) {
                                        if (useCache) {
                                            (node[expando] || (node[expando] = {}))[type] = [ dirruns, diff ];
                                        }
                                        if (node === elem) {
                                            break;
                                        }
                                    }
                                }
                            }
                            diff -= last;
                            return diff === first || diff % first === 0 && diff / first >= 0;
                        }
                    };
                },
                PSEUDO: function(pseudo, argument) {
                    var args, fn = Expr.pseudos[pseudo] || Expr.setFilters[pseudo.toLowerCase()] || Sizzle.error("unsupported pseudo: " + pseudo);
                    if (fn[expando]) {
                        return fn(argument);
                    }
                    if (fn.length > 1) {
                        args = [ pseudo, pseudo, "", argument ];
                        return Expr.setFilters.hasOwnProperty(pseudo.toLowerCase()) ? markFunction(function(seed, matches) {
                            var idx, matched = fn(seed, argument), i = matched.length;
                            while (i--) {
                                idx = indexOf(seed, matched[i]);
                                seed[idx] = !(matches[idx] = matched[i]);
                            }
                        }) : function(elem) {
                            return fn(elem, 0, args);
                        };
                    }
                    return fn;
                }
            },
            pseudos: {
                not: markFunction(function(selector) {
                    var input = [], results = [], matcher = compile(selector.replace(rtrim, "$1"));
                    return matcher[expando] ? markFunction(function(seed, matches, context, xml) {
                        var elem, unmatched = matcher(seed, null, xml, []), i = seed.length;
                        while (i--) {
                            if (elem = unmatched[i]) {
                                seed[i] = !(matches[i] = elem);
                            }
                        }
                    }) : function(elem, context, xml) {
                        input[0] = elem;
                        matcher(input, null, xml, results);
                        input[0] = null;
                        return !results.pop();
                    };
                }),
                has: markFunction(function(selector) {
                    return function(elem) {
                        return Sizzle(selector, elem).length > 0;
                    };
                }),
                contains: markFunction(function(text) {
                    text = text.replace(runescape, funescape);
                    return function(elem) {
                        return (elem.textContent || elem.innerText || getText(elem)).indexOf(text) > -1;
                    };
                }),
                lang: markFunction(function(lang) {
                    if (!ridentifier.test(lang || "")) {
                        Sizzle.error("unsupported lang: " + lang);
                    }
                    lang = lang.replace(runescape, funescape).toLowerCase();
                    return function(elem) {
                        var elemLang;
                        do {
                            if (elemLang = documentIsHTML ? elem.lang : elem.getAttribute("xml:lang") || elem.getAttribute("lang")) {
                                elemLang = elemLang.toLowerCase();
                                return elemLang === lang || elemLang.indexOf(lang + "-") === 0;
                            }
                        } while ((elem = elem.parentNode) && elem.nodeType === 1);
                        return false;
                    };
                }),
                target: function(elem) {
                    var hash = window.location && window.location.hash;
                    return hash && hash.slice(1) === elem.id;
                },
                root: function(elem) {
                    return elem === docElem;
                },
                focus: function(elem) {
                    return elem === document.activeElement && (!document.hasFocus || document.hasFocus()) && !!(elem.type || elem.href || ~elem.tabIndex);
                },
                enabled: function(elem) {
                    return elem.disabled === false;
                },
                disabled: function(elem) {
                    return elem.disabled === true;
                },
                checked: function(elem) {
                    var nodeName = elem.nodeName.toLowerCase();
                    return nodeName === "input" && !!elem.checked || nodeName === "option" && !!elem.selected;
                },
                selected: function(elem) {
                    if (elem.parentNode) {
                        elem.parentNode.selectedIndex;
                    }
                    return elem.selected === true;
                },
                empty: function(elem) {
                    for (elem = elem.firstChild; elem; elem = elem.nextSibling) {
                        if (elem.nodeType < 6) {
                            return false;
                        }
                    }
                    return true;
                },
                parent: function(elem) {
                    return !Expr.pseudos["empty"](elem);
                },
                header: function(elem) {
                    return rheader.test(elem.nodeName);
                },
                input: function(elem) {
                    return rinputs.test(elem.nodeName);
                },
                button: function(elem) {
                    var name = elem.nodeName.toLowerCase();
                    return name === "input" && elem.type === "button" || name === "button";
                },
                text: function(elem) {
                    var attr;
                    return elem.nodeName.toLowerCase() === "input" && elem.type === "text" && ((attr = elem.getAttribute("type")) == null || attr.toLowerCase() === "text");
                },
                first: createPositionalPseudo(function() {
                    return [ 0 ];
                }),
                last: createPositionalPseudo(function(matchIndexes, length) {
                    return [ length - 1 ];
                }),
                eq: createPositionalPseudo(function(matchIndexes, length, argument) {
                    return [ argument < 0 ? argument + length : argument ];
                }),
                even: createPositionalPseudo(function(matchIndexes, length) {
                    var i = 0;
                    for (;i < length; i += 2) {
                        matchIndexes.push(i);
                    }
                    return matchIndexes;
                }),
                odd: createPositionalPseudo(function(matchIndexes, length) {
                    var i = 1;
                    for (;i < length; i += 2) {
                        matchIndexes.push(i);
                    }
                    return matchIndexes;
                }),
                lt: createPositionalPseudo(function(matchIndexes, length, argument) {
                    var i = argument < 0 ? argument + length : argument;
                    for (;--i >= 0; ) {
                        matchIndexes.push(i);
                    }
                    return matchIndexes;
                }),
                gt: createPositionalPseudo(function(matchIndexes, length, argument) {
                    var i = argument < 0 ? argument + length : argument;
                    for (;++i < length; ) {
                        matchIndexes.push(i);
                    }
                    return matchIndexes;
                })
            }
        };
        Expr.pseudos["nth"] = Expr.pseudos["eq"];
        for (i in {
            radio: true,
            checkbox: true,
            file: true,
            password: true,
            image: true
        }) {
            Expr.pseudos[i] = createInputPseudo(i);
        }
        for (i in {
            submit: true,
            reset: true
        }) {
            Expr.pseudos[i] = createButtonPseudo(i);
        }
        function setFilters() {}
        setFilters.prototype = Expr.filters = Expr.pseudos;
        Expr.setFilters = new setFilters();
        tokenize = Sizzle.tokenize = function(selector, parseOnly) {
            var matched, match, tokens, type, soFar, groups, preFilters, cached = tokenCache[selector + " "];
            if (cached) {
                return parseOnly ? 0 : cached.slice(0);
            }
            soFar = selector;
            groups = [];
            preFilters = Expr.preFilter;
            while (soFar) {
                if (!matched || (match = rcomma.exec(soFar))) {
                    if (match) {
                        soFar = soFar.slice(match[0].length) || soFar;
                    }
                    groups.push(tokens = []);
                }
                matched = false;
                if (match = rcombinators.exec(soFar)) {
                    matched = match.shift();
                    tokens.push({
                        value: matched,
                        type: match[0].replace(rtrim, " ")
                    });
                    soFar = soFar.slice(matched.length);
                }
                for (type in Expr.filter) {
                    if ((match = matchExpr[type].exec(soFar)) && (!preFilters[type] || (match = preFilters[type](match)))) {
                        matched = match.shift();
                        tokens.push({
                            value: matched,
                            type: type,
                            matches: match
                        });
                        soFar = soFar.slice(matched.length);
                    }
                }
                if (!matched) {
                    break;
                }
            }
            return parseOnly ? soFar.length : soFar ? Sizzle.error(selector) : tokenCache(selector, groups).slice(0);
        };
        function toSelector(tokens) {
            var i = 0, len = tokens.length, selector = "";
            for (;i < len; i++) {
                selector += tokens[i].value;
            }
            return selector;
        }
        function addCombinator(matcher, combinator, base) {
            var dir = combinator.dir, checkNonElements = base && dir === "parentNode", doneName = done++;
            return combinator.first ? function(elem, context, xml) {
                while (elem = elem[dir]) {
                    if (elem.nodeType === 1 || checkNonElements) {
                        return matcher(elem, context, xml);
                    }
                }
            } : function(elem, context, xml) {
                var oldCache, outerCache, newCache = [ dirruns, doneName ];
                if (xml) {
                    while (elem = elem[dir]) {
                        if (elem.nodeType === 1 || checkNonElements) {
                            if (matcher(elem, context, xml)) {
                                return true;
                            }
                        }
                    }
                } else {
                    while (elem = elem[dir]) {
                        if (elem.nodeType === 1 || checkNonElements) {
                            outerCache = elem[expando] || (elem[expando] = {});
                            if ((oldCache = outerCache[dir]) && oldCache[0] === dirruns && oldCache[1] === doneName) {
                                return newCache[2] = oldCache[2];
                            } else {
                                outerCache[dir] = newCache;
                                if (newCache[2] = matcher(elem, context, xml)) {
                                    return true;
                                }
                            }
                        }
                    }
                }
            };
        }
        function elementMatcher(matchers) {
            return matchers.length > 1 ? function(elem, context, xml) {
                var i = matchers.length;
                while (i--) {
                    if (!matchers[i](elem, context, xml)) {
                        return false;
                    }
                }
                return true;
            } : matchers[0];
        }
        function multipleContexts(selector, contexts, results) {
            var i = 0, len = contexts.length;
            for (;i < len; i++) {
                Sizzle(selector, contexts[i], results);
            }
            return results;
        }
        function condense(unmatched, map, filter, context, xml) {
            var elem, newUnmatched = [], i = 0, len = unmatched.length, mapped = map != null;
            for (;i < len; i++) {
                if (elem = unmatched[i]) {
                    if (!filter || filter(elem, context, xml)) {
                        newUnmatched.push(elem);
                        if (mapped) {
                            map.push(i);
                        }
                    }
                }
            }
            return newUnmatched;
        }
        function setMatcher(preFilter, selector, matcher, postFilter, postFinder, postSelector) {
            if (postFilter && !postFilter[expando]) {
                postFilter = setMatcher(postFilter);
            }
            if (postFinder && !postFinder[expando]) {
                postFinder = setMatcher(postFinder, postSelector);
            }
            return markFunction(function(seed, results, context, xml) {
                var temp, i, elem, preMap = [], postMap = [], preexisting = results.length, elems = seed || multipleContexts(selector || "*", context.nodeType ? [ context ] : context, []), matcherIn = preFilter && (seed || !selector) ? condense(elems, preMap, preFilter, context, xml) : elems, matcherOut = matcher ? postFinder || (seed ? preFilter : preexisting || postFilter) ? [] : results : matcherIn;
                if (matcher) {
                    matcher(matcherIn, matcherOut, context, xml);
                }
                if (postFilter) {
                    temp = condense(matcherOut, postMap);
                    postFilter(temp, [], context, xml);
                    i = temp.length;
                    while (i--) {
                        if (elem = temp[i]) {
                            matcherOut[postMap[i]] = !(matcherIn[postMap[i]] = elem);
                        }
                    }
                }
                if (seed) {
                    if (postFinder || preFilter) {
                        if (postFinder) {
                            temp = [];
                            i = matcherOut.length;
                            while (i--) {
                                if (elem = matcherOut[i]) {
                                    temp.push(matcherIn[i] = elem);
                                }
                            }
                            postFinder(null, matcherOut = [], temp, xml);
                        }
                        i = matcherOut.length;
                        while (i--) {
                            if ((elem = matcherOut[i]) && (temp = postFinder ? indexOf(seed, elem) : preMap[i]) > -1) {
                                seed[temp] = !(results[temp] = elem);
                            }
                        }
                    }
                } else {
                    matcherOut = condense(matcherOut === results ? matcherOut.splice(preexisting, matcherOut.length) : matcherOut);
                    if (postFinder) {
                        postFinder(null, results, matcherOut, xml);
                    } else {
                        push.apply(results, matcherOut);
                    }
                }
            });
        }
        function matcherFromTokens(tokens) {
            var checkContext, matcher, j, len = tokens.length, leadingRelative = Expr.relative[tokens[0].type], implicitRelative = leadingRelative || Expr.relative[" "], i = leadingRelative ? 1 : 0, matchContext = addCombinator(function(elem) {
                return elem === checkContext;
            }, implicitRelative, true), matchAnyContext = addCombinator(function(elem) {
                return indexOf(checkContext, elem) > -1;
            }, implicitRelative, true), matchers = [ function(elem, context, xml) {
                var ret = !leadingRelative && (xml || context !== outermostContext) || ((checkContext = context).nodeType ? matchContext(elem, context, xml) : matchAnyContext(elem, context, xml));
                checkContext = null;
                return ret;
            } ];
            for (;i < len; i++) {
                if (matcher = Expr.relative[tokens[i].type]) {
                    matchers = [ addCombinator(elementMatcher(matchers), matcher) ];
                } else {
                    matcher = Expr.filter[tokens[i].type].apply(null, tokens[i].matches);
                    if (matcher[expando]) {
                        j = ++i;
                        for (;j < len; j++) {
                            if (Expr.relative[tokens[j].type]) {
                                break;
                            }
                        }
                        return setMatcher(i > 1 && elementMatcher(matchers), i > 1 && toSelector(tokens.slice(0, i - 1).concat({
                            value: tokens[i - 2].type === " " ? "*" : ""
                        })).replace(rtrim, "$1"), matcher, i < j && matcherFromTokens(tokens.slice(i, j)), j < len && matcherFromTokens(tokens = tokens.slice(j)), j < len && toSelector(tokens));
                    }
                    matchers.push(matcher);
                }
            }
            return elementMatcher(matchers);
        }
        function matcherFromGroupMatchers(elementMatchers, setMatchers) {
            var bySet = setMatchers.length > 0, byElement = elementMatchers.length > 0, superMatcher = function(seed, context, xml, results, outermost) {
                var elem, j, matcher, matchedCount = 0, i = "0", unmatched = seed && [], setMatched = [], contextBackup = outermostContext, elems = seed || byElement && Expr.find["TAG"]("*", outermost), dirrunsUnique = dirruns += contextBackup == null ? 1 : Math.random() || .1, len = elems.length;
                if (outermost) {
                    outermostContext = context !== document && context;
                }
                for (;i !== len && (elem = elems[i]) != null; i++) {
                    if (byElement && elem) {
                        j = 0;
                        while (matcher = elementMatchers[j++]) {
                            if (matcher(elem, context, xml)) {
                                results.push(elem);
                                break;
                            }
                        }
                        if (outermost) {
                            dirruns = dirrunsUnique;
                        }
                    }
                    if (bySet) {
                        if (elem = !matcher && elem) {
                            matchedCount--;
                        }
                        if (seed) {
                            unmatched.push(elem);
                        }
                    }
                }
                matchedCount += i;
                if (bySet && i !== matchedCount) {
                    j = 0;
                    while (matcher = setMatchers[j++]) {
                        matcher(unmatched, setMatched, context, xml);
                    }
                    if (seed) {
                        if (matchedCount > 0) {
                            while (i--) {
                                if (!(unmatched[i] || setMatched[i])) {
                                    setMatched[i] = pop.call(results);
                                }
                            }
                        }
                        setMatched = condense(setMatched);
                    }
                    push.apply(results, setMatched);
                    if (outermost && !seed && setMatched.length > 0 && matchedCount + setMatchers.length > 1) {
                        Sizzle.uniqueSort(results);
                    }
                }
                if (outermost) {
                    dirruns = dirrunsUnique;
                    outermostContext = contextBackup;
                }
                return unmatched;
            };
            return bySet ? markFunction(superMatcher) : superMatcher;
        }
        compile = Sizzle.compile = function(selector, match) {
            var i, setMatchers = [], elementMatchers = [], cached = compilerCache[selector + " "];
            if (!cached) {
                if (!match) {
                    match = tokenize(selector);
                }
                i = match.length;
                while (i--) {
                    cached = matcherFromTokens(match[i]);
                    if (cached[expando]) {
                        setMatchers.push(cached);
                    } else {
                        elementMatchers.push(cached);
                    }
                }
                cached = compilerCache(selector, matcherFromGroupMatchers(elementMatchers, setMatchers));
                cached.selector = selector;
            }
            return cached;
        };
        select = Sizzle.select = function(selector, context, results, seed) {
            var i, tokens, token, type, find, compiled = typeof selector === "function" && selector, match = !seed && tokenize(selector = compiled.selector || selector);
            results = results || [];
            if (match.length === 1) {
                tokens = match[0] = match[0].slice(0);
                if (tokens.length > 2 && (token = tokens[0]).type === "ID" && support.getById && context.nodeType === 9 && documentIsHTML && Expr.relative[tokens[1].type]) {
                    context = (Expr.find["ID"](token.matches[0].replace(runescape, funescape), context) || [])[0];
                    if (!context) {
                        return results;
                    } else if (compiled) {
                        context = context.parentNode;
                    }
                    selector = selector.slice(tokens.shift().value.length);
                }
                i = matchExpr["needsContext"].test(selector) ? 0 : tokens.length;
                while (i--) {
                    token = tokens[i];
                    if (Expr.relative[type = token.type]) {
                        break;
                    }
                    if (find = Expr.find[type]) {
                        if (seed = find(token.matches[0].replace(runescape, funescape), rsibling.test(tokens[0].type) && testContext(context.parentNode) || context)) {
                            tokens.splice(i, 1);
                            selector = seed.length && toSelector(tokens);
                            if (!selector) {
                                push.apply(results, seed);
                                return results;
                            }
                            break;
                        }
                    }
                }
            }
            (compiled || compile(selector, match))(seed, context, !documentIsHTML, results, rsibling.test(selector) && testContext(context.parentNode) || context);
            return results;
        };
        support.sortStable = expando.split("").sort(sortOrder).join("") === expando;
        support.detectDuplicates = !!hasDuplicate;
        setDocument();
        support.sortDetached = assert(function(div1) {
            return div1.compareDocumentPosition(document.createElement("div")) & 1;
        });
        if (!assert(function(div) {
            div.innerHTML = "<a href='#'></a>";
            return div.firstChild.getAttribute("href") === "#";
        })) {
            addHandle("type|href|height|width", function(elem, name, isXML) {
                if (!isXML) {
                    return elem.getAttribute(name, name.toLowerCase() === "type" ? 1 : 2);
                }
            });
        }
        if (!support.attributes || !assert(function(div) {
            div.innerHTML = "<input/>";
            div.firstChild.setAttribute("value", "");
            return div.firstChild.getAttribute("value") === "";
        })) {
            addHandle("value", function(elem, name, isXML) {
                if (!isXML && elem.nodeName.toLowerCase() === "input") {
                    return elem.defaultValue;
                }
            });
        }
        if (!assert(function(div) {
            return div.getAttribute("disabled") == null;
        })) {
            addHandle(booleans, function(elem, name, isXML) {
                var val;
                if (!isXML) {
                    return elem[name] === true ? name.toLowerCase() : (val = elem.getAttributeNode(name)) && val.specified ? val.value : null;
                }
            });
        }
        return Sizzle;
    }(window);
    jQuery.find = Sizzle;
    jQuery.expr = Sizzle.selectors;
    jQuery.expr[":"] = jQuery.expr.pseudos;
    jQuery.unique = Sizzle.uniqueSort;
    jQuery.text = Sizzle.getText;
    jQuery.isXMLDoc = Sizzle.isXML;
    jQuery.contains = Sizzle.contains;
    var rneedsContext = jQuery.expr.match.needsContext;
    var rsingleTag = /^<(\w+)\s*\/?>(?:<\/\1>|)$/;
    var risSimple = /^.[^:#\[\.,]*$/;
    function winnow(elements, qualifier, not) {
        if (jQuery.isFunction(qualifier)) {
            return jQuery.grep(elements, function(elem, i) {
                return !!qualifier.call(elem, i, elem) !== not;
            });
        }
        if (qualifier.nodeType) {
            return jQuery.grep(elements, function(elem) {
                return elem === qualifier !== not;
            });
        }
        if (typeof qualifier === "string") {
            if (risSimple.test(qualifier)) {
                return jQuery.filter(qualifier, elements, not);
            }
            qualifier = jQuery.filter(qualifier, elements);
        }
        return jQuery.grep(elements, function(elem) {
            return indexOf.call(qualifier, elem) >= 0 !== not;
        });
    }
    jQuery.filter = function(expr, elems, not) {
        var elem = elems[0];
        if (not) {
            expr = ":not(" + expr + ")";
        }
        return elems.length === 1 && elem.nodeType === 1 ? jQuery.find.matchesSelector(elem, expr) ? [ elem ] : [] : jQuery.find.matches(expr, jQuery.grep(elems, function(elem) {
            return elem.nodeType === 1;
        }));
    };
    jQuery.fn.extend({
        find: function(selector) {
            var i, len = this.length, ret = [], self = this;
            if (typeof selector !== "string") {
                return this.pushStack(jQuery(selector).filter(function() {
                    for (i = 0; i < len; i++) {
                        if (jQuery.contains(self[i], this)) {
                            return true;
                        }
                    }
                }));
            }
            for (i = 0; i < len; i++) {
                jQuery.find(selector, self[i], ret);
            }
            ret = this.pushStack(len > 1 ? jQuery.unique(ret) : ret);
            ret.selector = this.selector ? this.selector + " " + selector : selector;
            return ret;
        },
        filter: function(selector) {
            return this.pushStack(winnow(this, selector || [], false));
        },
        not: function(selector) {
            return this.pushStack(winnow(this, selector || [], true));
        },
        is: function(selector) {
            return !!winnow(this, typeof selector === "string" && rneedsContext.test(selector) ? jQuery(selector) : selector || [], false).length;
        }
    });
    var rootjQuery, rquickExpr = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/, init = jQuery.fn.init = function(selector, context) {
        var match, elem;
        if (!selector) {
            return this;
        }
        if (typeof selector === "string") {
            if (selector[0] === "<" && selector[selector.length - 1] === ">" && selector.length >= 3) {
                match = [ null, selector, null ];
            } else {
                match = rquickExpr.exec(selector);
            }
            if (match && (match[1] || !context)) {
                if (match[1]) {
                    context = context instanceof jQuery ? context[0] : context;
                    jQuery.merge(this, jQuery.parseHTML(match[1], context && context.nodeType ? context.ownerDocument || context : document, true));
                    if (rsingleTag.test(match[1]) && jQuery.isPlainObject(context)) {
                        for (match in context) {
                            if (jQuery.isFunction(this[match])) {
                                this[match](context[match]);
                            } else {
                                this.attr(match, context[match]);
                            }
                        }
                    }
                    return this;
                } else {
                    elem = document.getElementById(match[2]);
                    if (elem && elem.parentNode) {
                        this.length = 1;
                        this[0] = elem;
                    }
                    this.context = document;
                    this.selector = selector;
                    return this;
                }
            } else if (!context || context.jquery) {
                return (context || rootjQuery).find(selector);
            } else {
                return this.constructor(context).find(selector);
            }
        } else if (selector.nodeType) {
            this.context = this[0] = selector;
            this.length = 1;
            return this;
        } else if (jQuery.isFunction(selector)) {
            return typeof rootjQuery.ready !== "undefined" ? rootjQuery.ready(selector) : selector(jQuery);
        }
        if (selector.selector !== undefined) {
            this.selector = selector.selector;
            this.context = selector.context;
        }
        return jQuery.makeArray(selector, this);
    };
    init.prototype = jQuery.fn;
    rootjQuery = jQuery(document);
    var rparentsprev = /^(?:parents|prev(?:Until|All))/, guaranteedUnique = {
        children: true,
        contents: true,
        next: true,
        prev: true
    };
    jQuery.extend({
        dir: function(elem, dir, until) {
            var matched = [], truncate = until !== undefined;
            while ((elem = elem[dir]) && elem.nodeType !== 9) {
                if (elem.nodeType === 1) {
                    if (truncate && jQuery(elem).is(until)) {
                        break;
                    }
                    matched.push(elem);
                }
            }
            return matched;
        },
        sibling: function(n, elem) {
            var matched = [];
            for (;n; n = n.nextSibling) {
                if (n.nodeType === 1 && n !== elem) {
                    matched.push(n);
                }
            }
            return matched;
        }
    });
    jQuery.fn.extend({
        has: function(target) {
            var targets = jQuery(target, this), l = targets.length;
            return this.filter(function() {
                var i = 0;
                for (;i < l; i++) {
                    if (jQuery.contains(this, targets[i])) {
                        return true;
                    }
                }
            });
        },
        closest: function(selectors, context) {
            var cur, i = 0, l = this.length, matched = [], pos = rneedsContext.test(selectors) || typeof selectors !== "string" ? jQuery(selectors, context || this.context) : 0;
            for (;i < l; i++) {
                for (cur = this[i]; cur && cur !== context; cur = cur.parentNode) {
                    if (cur.nodeType < 11 && (pos ? pos.index(cur) > -1 : cur.nodeType === 1 && jQuery.find.matchesSelector(cur, selectors))) {
                        matched.push(cur);
                        break;
                    }
                }
            }
            return this.pushStack(matched.length > 1 ? jQuery.unique(matched) : matched);
        },
        index: function(elem) {
            if (!elem) {
                return this[0] && this[0].parentNode ? this.first().prevAll().length : -1;
            }
            if (typeof elem === "string") {
                return indexOf.call(jQuery(elem), this[0]);
            }
            return indexOf.call(this, elem.jquery ? elem[0] : elem);
        },
        add: function(selector, context) {
            return this.pushStack(jQuery.unique(jQuery.merge(this.get(), jQuery(selector, context))));
        },
        addBack: function(selector) {
            return this.add(selector == null ? this.prevObject : this.prevObject.filter(selector));
        }
    });
    function sibling(cur, dir) {
        while ((cur = cur[dir]) && cur.nodeType !== 1) {}
        return cur;
    }
    jQuery.each({
        parent: function(elem) {
            var parent = elem.parentNode;
            return parent && parent.nodeType !== 11 ? parent : null;
        },
        parents: function(elem) {
            return jQuery.dir(elem, "parentNode");
        },
        parentsUntil: function(elem, i, until) {
            return jQuery.dir(elem, "parentNode", until);
        },
        next: function(elem) {
            return sibling(elem, "nextSibling");
        },
        prev: function(elem) {
            return sibling(elem, "previousSibling");
        },
        nextAll: function(elem) {
            return jQuery.dir(elem, "nextSibling");
        },
        prevAll: function(elem) {
            return jQuery.dir(elem, "previousSibling");
        },
        nextUntil: function(elem, i, until) {
            return jQuery.dir(elem, "nextSibling", until);
        },
        prevUntil: function(elem, i, until) {
            return jQuery.dir(elem, "previousSibling", until);
        },
        siblings: function(elem) {
            return jQuery.sibling((elem.parentNode || {}).firstChild, elem);
        },
        children: function(elem) {
            return jQuery.sibling(elem.firstChild);
        },
        contents: function(elem) {
            return elem.contentDocument || jQuery.merge([], elem.childNodes);
        }
    }, function(name, fn) {
        jQuery.fn[name] = function(until, selector) {
            var matched = jQuery.map(this, fn, until);
            if (name.slice(-5) !== "Until") {
                selector = until;
            }
            if (selector && typeof selector === "string") {
                matched = jQuery.filter(selector, matched);
            }
            if (this.length > 1) {
                if (!guaranteedUnique[name]) {
                    jQuery.unique(matched);
                }
                if (rparentsprev.test(name)) {
                    matched.reverse();
                }
            }
            return this.pushStack(matched);
        };
    });
    var rnotwhite = /\S+/g;
    var optionsCache = {};
    function createOptions(options) {
        var object = optionsCache[options] = {};
        jQuery.each(options.match(rnotwhite) || [], function(_, flag) {
            object[flag] = true;
        });
        return object;
    }
    jQuery.Callbacks = function(options) {
        options = typeof options === "string" ? optionsCache[options] || createOptions(options) : jQuery.extend({}, options);
        var memory, fired, firing, firingStart, firingLength, firingIndex, list = [], stack = !options.once && [], fire = function(data) {
            memory = options.memory && data;
            fired = true;
            firingIndex = firingStart || 0;
            firingStart = 0;
            firingLength = list.length;
            firing = true;
            for (;list && firingIndex < firingLength; firingIndex++) {
                if (list[firingIndex].apply(data[0], data[1]) === false && options.stopOnFalse) {
                    memory = false;
                    break;
                }
            }
            firing = false;
            if (list) {
                if (stack) {
                    if (stack.length) {
                        fire(stack.shift());
                    }
                } else if (memory) {
                    list = [];
                } else {
                    self.disable();
                }
            }
        }, self = {
            add: function() {
                if (list) {
                    var start = list.length;
                    (function add(args) {
                        jQuery.each(args, function(_, arg) {
                            var type = jQuery.type(arg);
                            if (type === "function") {
                                if (!options.unique || !self.has(arg)) {
                                    list.push(arg);
                                }
                            } else if (arg && arg.length && type !== "string") {
                                add(arg);
                            }
                        });
                    })(arguments);
                    if (firing) {
                        firingLength = list.length;
                    } else if (memory) {
                        firingStart = start;
                        fire(memory);
                    }
                }
                return this;
            },
            remove: function() {
                if (list) {
                    jQuery.each(arguments, function(_, arg) {
                        var index;
                        while ((index = jQuery.inArray(arg, list, index)) > -1) {
                            list.splice(index, 1);
                            if (firing) {
                                if (index <= firingLength) {
                                    firingLength--;
                                }
                                if (index <= firingIndex) {
                                    firingIndex--;
                                }
                            }
                        }
                    });
                }
                return this;
            },
            has: function(fn) {
                return fn ? jQuery.inArray(fn, list) > -1 : !!(list && list.length);
            },
            empty: function() {
                list = [];
                firingLength = 0;
                return this;
            },
            disable: function() {
                list = stack = memory = undefined;
                return this;
            },
            disabled: function() {
                return !list;
            },
            lock: function() {
                stack = undefined;
                if (!memory) {
                    self.disable();
                }
                return this;
            },
            locked: function() {
                return !stack;
            },
            fireWith: function(context, args) {
                if (list && (!fired || stack)) {
                    args = args || [];
                    args = [ context, args.slice ? args.slice() : args ];
                    if (firing) {
                        stack.push(args);
                    } else {
                        fire(args);
                    }
                }
                return this;
            },
            fire: function() {
                self.fireWith(this, arguments);
                return this;
            },
            fired: function() {
                return !!fired;
            }
        };
        return self;
    };
    jQuery.extend({
        Deferred: function(func) {
            var tuples = [ [ "resolve", "done", jQuery.Callbacks("once memory"), "resolved" ], [ "reject", "fail", jQuery.Callbacks("once memory"), "rejected" ], [ "notify", "progress", jQuery.Callbacks("memory") ] ], state = "pending", promise = {
                state: function() {
                    return state;
                },
                always: function() {
                    deferred.done(arguments).fail(arguments);
                    return this;
                },
                then: function() {
                    var fns = arguments;
                    return jQuery.Deferred(function(newDefer) {
                        jQuery.each(tuples, function(i, tuple) {
                            var fn = jQuery.isFunction(fns[i]) && fns[i];
                            deferred[tuple[1]](function() {
                                var returned = fn && fn.apply(this, arguments);
                                if (returned && jQuery.isFunction(returned.promise)) {
                                    returned.promise().done(newDefer.resolve).fail(newDefer.reject).progress(newDefer.notify);
                                } else {
                                    newDefer[tuple[0] + "With"](this === promise ? newDefer.promise() : this, fn ? [ returned ] : arguments);
                                }
                            });
                        });
                        fns = null;
                    }).promise();
                },
                promise: function(obj) {
                    return obj != null ? jQuery.extend(obj, promise) : promise;
                }
            }, deferred = {};
            promise.pipe = promise.then;
            jQuery.each(tuples, function(i, tuple) {
                var list = tuple[2], stateString = tuple[3];
                promise[tuple[1]] = list.add;
                if (stateString) {
                    list.add(function() {
                        state = stateString;
                    }, tuples[i ^ 1][2].disable, tuples[2][2].lock);
                }
                deferred[tuple[0]] = function() {
                    deferred[tuple[0] + "With"](this === deferred ? promise : this, arguments);
                    return this;
                };
                deferred[tuple[0] + "With"] = list.fireWith;
            });
            promise.promise(deferred);
            if (func) {
                func.call(deferred, deferred);
            }
            return deferred;
        },
        when: function(subordinate) {
            var i = 0, resolveValues = slice.call(arguments), length = resolveValues.length, remaining = length !== 1 || subordinate && jQuery.isFunction(subordinate.promise) ? length : 0, deferred = remaining === 1 ? subordinate : jQuery.Deferred(), updateFunc = function(i, contexts, values) {
                return function(value) {
                    contexts[i] = this;
                    values[i] = arguments.length > 1 ? slice.call(arguments) : value;
                    if (values === progressValues) {
                        deferred.notifyWith(contexts, values);
                    } else if (!--remaining) {
                        deferred.resolveWith(contexts, values);
                    }
                };
            }, progressValues, progressContexts, resolveContexts;
            if (length > 1) {
                progressValues = new Array(length);
                progressContexts = new Array(length);
                resolveContexts = new Array(length);
                for (;i < length; i++) {
                    if (resolveValues[i] && jQuery.isFunction(resolveValues[i].promise)) {
                        resolveValues[i].promise().done(updateFunc(i, resolveContexts, resolveValues)).fail(deferred.reject).progress(updateFunc(i, progressContexts, progressValues));
                    } else {
                        --remaining;
                    }
                }
            }
            if (!remaining) {
                deferred.resolveWith(resolveContexts, resolveValues);
            }
            return deferred.promise();
        }
    });
    var readyList;
    jQuery.fn.ready = function(fn) {
        jQuery.ready.promise().done(fn);
        return this;
    };
    jQuery.extend({
        isReady: false,
        readyWait: 1,
        holdReady: function(hold) {
            if (hold) {
                jQuery.readyWait++;
            } else {
                jQuery.ready(true);
            }
        },
        ready: function(wait) {
            if (wait === true ? --jQuery.readyWait : jQuery.isReady) {
                return;
            }
            jQuery.isReady = true;
            if (wait !== true && --jQuery.readyWait > 0) {
                return;
            }
            readyList.resolveWith(document, [ jQuery ]);
            if (jQuery.fn.triggerHandler) {
                jQuery(document).triggerHandler("ready");
                jQuery(document).off("ready");
            }
        }
    });
    function completed() {
        document.removeEventListener("DOMContentLoaded", completed, false);
        window.removeEventListener("load", completed, false);
        jQuery.ready();
    }
    jQuery.ready.promise = function(obj) {
        if (!readyList) {
            readyList = jQuery.Deferred();
            if (document.readyState === "complete") {
                setTimeout(jQuery.ready);
            } else {
                document.addEventListener("DOMContentLoaded", completed, false);
                window.addEventListener("load", completed, false);
            }
        }
        return readyList.promise(obj);
    };
    jQuery.ready.promise();
    var access = jQuery.access = function(elems, fn, key, value, chainable, emptyGet, raw) {
        var i = 0, len = elems.length, bulk = key == null;
        if (jQuery.type(key) === "object") {
            chainable = true;
            for (i in key) {
                jQuery.access(elems, fn, i, key[i], true, emptyGet, raw);
            }
        } else if (value !== undefined) {
            chainable = true;
            if (!jQuery.isFunction(value)) {
                raw = true;
            }
            if (bulk) {
                if (raw) {
                    fn.call(elems, value);
                    fn = null;
                } else {
                    bulk = fn;
                    fn = function(elem, key, value) {
                        return bulk.call(jQuery(elem), value);
                    };
                }
            }
            if (fn) {
                for (;i < len; i++) {
                    fn(elems[i], key, raw ? value : value.call(elems[i], i, fn(elems[i], key)));
                }
            }
        }
        return chainable ? elems : bulk ? fn.call(elems) : len ? fn(elems[0], key) : emptyGet;
    };
    jQuery.acceptData = function(owner) {
        return owner.nodeType === 1 || owner.nodeType === 9 || !+owner.nodeType;
    };
    function Data() {
        Object.defineProperty(this.cache = {}, 0, {
            get: function() {
                return {};
            }
        });
        this.expando = jQuery.expando + Data.uid++;
    }
    Data.uid = 1;
    Data.accepts = jQuery.acceptData;
    Data.prototype = {
        key: function(owner) {
            if (!Data.accepts(owner)) {
                return 0;
            }
            var descriptor = {}, unlock = owner[this.expando];
            if (!unlock) {
                unlock = Data.uid++;
                try {
                    descriptor[this.expando] = {
                        value: unlock
                    };
                    Object.defineProperties(owner, descriptor);
                } catch (e) {
                    descriptor[this.expando] = unlock;
                    jQuery.extend(owner, descriptor);
                }
            }
            if (!this.cache[unlock]) {
                this.cache[unlock] = {};
            }
            return unlock;
        },
        set: function(owner, data, value) {
            var prop, unlock = this.key(owner), cache = this.cache[unlock];
            if (typeof data === "string") {
                cache[data] = value;
            } else {
                if (jQuery.isEmptyObject(cache)) {
                    jQuery.extend(this.cache[unlock], data);
                } else {
                    for (prop in data) {
                        cache[prop] = data[prop];
                    }
                }
            }
            return cache;
        },
        get: function(owner, key) {
            var cache = this.cache[this.key(owner)];
            return key === undefined ? cache : cache[key];
        },
        access: function(owner, key, value) {
            var stored;
            if (key === undefined || key && typeof key === "string" && value === undefined) {
                stored = this.get(owner, key);
                return stored !== undefined ? stored : this.get(owner, jQuery.camelCase(key));
            }
            this.set(owner, key, value);
            return value !== undefined ? value : key;
        },
        remove: function(owner, key) {
            var i, name, camel, unlock = this.key(owner), cache = this.cache[unlock];
            if (key === undefined) {
                this.cache[unlock] = {};
            } else {
                if (jQuery.isArray(key)) {
                    name = key.concat(key.map(jQuery.camelCase));
                } else {
                    camel = jQuery.camelCase(key);
                    if (key in cache) {
                        name = [ key, camel ];
                    } else {
                        name = camel;
                        name = name in cache ? [ name ] : name.match(rnotwhite) || [];
                    }
                }
                i = name.length;
                while (i--) {
                    delete cache[name[i]];
                }
            }
        },
        hasData: function(owner) {
            return !jQuery.isEmptyObject(this.cache[owner[this.expando]] || {});
        },
        discard: function(owner) {
            if (owner[this.expando]) {
                delete this.cache[owner[this.expando]];
            }
        }
    };
    var data_priv = new Data();
    var data_user = new Data();
    var rbrace = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/, rmultiDash = /([A-Z])/g;
    function dataAttr(elem, key, data) {
        var name;
        if (data === undefined && elem.nodeType === 1) {
            name = "data-" + key.replace(rmultiDash, "-$1").toLowerCase();
            data = elem.getAttribute(name);
            if (typeof data === "string") {
                try {
                    data = data === "true" ? true : data === "false" ? false : data === "null" ? null : +data + "" === data ? +data : rbrace.test(data) ? jQuery.parseJSON(data) : data;
                } catch (e) {}
                data_user.set(elem, key, data);
            } else {
                data = undefined;
            }
        }
        return data;
    }
    jQuery.extend({
        hasData: function(elem) {
            return data_user.hasData(elem) || data_priv.hasData(elem);
        },
        data: function(elem, name, data) {
            return data_user.access(elem, name, data);
        },
        removeData: function(elem, name) {
            data_user.remove(elem, name);
        },
        _data: function(elem, name, data) {
            return data_priv.access(elem, name, data);
        },
        _removeData: function(elem, name) {
            data_priv.remove(elem, name);
        }
    });
    jQuery.fn.extend({
        data: function(key, value) {
            var i, name, data, elem = this[0], attrs = elem && elem.attributes;
            if (key === undefined) {
                if (this.length) {
                    data = data_user.get(elem);
                    if (elem.nodeType === 1 && !data_priv.get(elem, "hasDataAttrs")) {
                        i = attrs.length;
                        while (i--) {
                            if (attrs[i]) {
                                name = attrs[i].name;
                                if (name.indexOf("data-") === 0) {
                                    name = jQuery.camelCase(name.slice(5));
                                    dataAttr(elem, name, data[name]);
                                }
                            }
                        }
                        data_priv.set(elem, "hasDataAttrs", true);
                    }
                }
                return data;
            }
            if (typeof key === "object") {
                return this.each(function() {
                    data_user.set(this, key);
                });
            }
            return access(this, function(value) {
                var data, camelKey = jQuery.camelCase(key);
                if (elem && value === undefined) {
                    data = data_user.get(elem, key);
                    if (data !== undefined) {
                        return data;
                    }
                    data = data_user.get(elem, camelKey);
                    if (data !== undefined) {
                        return data;
                    }
                    data = dataAttr(elem, camelKey, undefined);
                    if (data !== undefined) {
                        return data;
                    }
                    return;
                }
                this.each(function() {
                    var data = data_user.get(this, camelKey);
                    data_user.set(this, camelKey, value);
                    if (key.indexOf("-") !== -1 && data !== undefined) {
                        data_user.set(this, key, value);
                    }
                });
            }, null, value, arguments.length > 1, null, true);
        },
        removeData: function(key) {
            return this.each(function() {
                data_user.remove(this, key);
            });
        }
    });
    jQuery.extend({
        queue: function(elem, type, data) {
            var queue;
            if (elem) {
                type = (type || "fx") + "queue";
                queue = data_priv.get(elem, type);
                if (data) {
                    if (!queue || jQuery.isArray(data)) {
                        queue = data_priv.access(elem, type, jQuery.makeArray(data));
                    } else {
                        queue.push(data);
                    }
                }
                return queue || [];
            }
        },
        dequeue: function(elem, type) {
            type = type || "fx";
            var queue = jQuery.queue(elem, type), startLength = queue.length, fn = queue.shift(), hooks = jQuery._queueHooks(elem, type), next = function() {
                jQuery.dequeue(elem, type);
            };
            if (fn === "inprogress") {
                fn = queue.shift();
                startLength--;
            }
            if (fn) {
                if (type === "fx") {
                    queue.unshift("inprogress");
                }
                delete hooks.stop;
                fn.call(elem, next, hooks);
            }
            if (!startLength && hooks) {
                hooks.empty.fire();
            }
        },
        _queueHooks: function(elem, type) {
            var key = type + "queueHooks";
            return data_priv.get(elem, key) || data_priv.access(elem, key, {
                empty: jQuery.Callbacks("once memory").add(function() {
                    data_priv.remove(elem, [ type + "queue", key ]);
                })
            });
        }
    });
    jQuery.fn.extend({
        queue: function(type, data) {
            var setter = 2;
            if (typeof type !== "string") {
                data = type;
                type = "fx";
                setter--;
            }
            if (arguments.length < setter) {
                return jQuery.queue(this[0], type);
            }
            return data === undefined ? this : this.each(function() {
                var queue = jQuery.queue(this, type, data);
                jQuery._queueHooks(this, type);
                if (type === "fx" && queue[0] !== "inprogress") {
                    jQuery.dequeue(this, type);
                }
            });
        },
        dequeue: function(type) {
            return this.each(function() {
                jQuery.dequeue(this, type);
            });
        },
        clearQueue: function(type) {
            return this.queue(type || "fx", []);
        },
        promise: function(type, obj) {
            var tmp, count = 1, defer = jQuery.Deferred(), elements = this, i = this.length, resolve = function() {
                if (!--count) {
                    defer.resolveWith(elements, [ elements ]);
                }
            };
            if (typeof type !== "string") {
                obj = type;
                type = undefined;
            }
            type = type || "fx";
            while (i--) {
                tmp = data_priv.get(elements[i], type + "queueHooks");
                if (tmp && tmp.empty) {
                    count++;
                    tmp.empty.add(resolve);
                }
            }
            resolve();
            return defer.promise(obj);
        }
    });
    var pnum = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source;
    var cssExpand = [ "Top", "Right", "Bottom", "Left" ];
    var isHidden = function(elem, el) {
        elem = el || elem;
        return jQuery.css(elem, "display") === "none" || !jQuery.contains(elem.ownerDocument, elem);
    };
    var rcheckableType = /^(?:checkbox|radio)$/i;
    (function() {
        var fragment = document.createDocumentFragment(), div = fragment.appendChild(document.createElement("div")), input = document.createElement("input");
        input.setAttribute("type", "radio");
        input.setAttribute("checked", "checked");
        input.setAttribute("name", "t");
        div.appendChild(input);
        support.checkClone = div.cloneNode(true).cloneNode(true).lastChild.checked;
        div.innerHTML = "<textarea>x</textarea>";
        support.noCloneChecked = !!div.cloneNode(true).lastChild.defaultValue;
    })();
    var strundefined = typeof undefined;
    support.focusinBubbles = "onfocusin" in window;
    var rkeyEvent = /^key/, rmouseEvent = /^(?:mouse|pointer|contextmenu)|click/, rfocusMorph = /^(?:focusinfocus|focusoutblur)$/, rtypenamespace = /^([^.]*)(?:\.(.+)|)$/;
    function returnTrue() {
        return true;
    }
    function returnFalse() {
        return false;
    }
    function safeActiveElement() {
        try {
            return document.activeElement;
        } catch (err) {}
    }
    jQuery.event = {
        global: {},
        add: function(elem, types, handler, data, selector) {
            var handleObjIn, eventHandle, tmp, events, t, handleObj, special, handlers, type, namespaces, origType, elemData = data_priv.get(elem);
            if (!elemData) {
                return;
            }
            if (handler.handler) {
                handleObjIn = handler;
                handler = handleObjIn.handler;
                selector = handleObjIn.selector;
            }
            if (!handler.guid) {
                handler.guid = jQuery.guid++;
            }
            if (!(events = elemData.events)) {
                events = elemData.events = {};
            }
            if (!(eventHandle = elemData.handle)) {
                eventHandle = elemData.handle = function(e) {
                    return typeof jQuery !== strundefined && jQuery.event.triggered !== e.type ? jQuery.event.dispatch.apply(elem, arguments) : undefined;
                };
            }
            types = (types || "").match(rnotwhite) || [ "" ];
            t = types.length;
            while (t--) {
                tmp = rtypenamespace.exec(types[t]) || [];
                type = origType = tmp[1];
                namespaces = (tmp[2] || "").split(".").sort();
                if (!type) {
                    continue;
                }
                special = jQuery.event.special[type] || {};
                type = (selector ? special.delegateType : special.bindType) || type;
                special = jQuery.event.special[type] || {};
                handleObj = jQuery.extend({
                    type: type,
                    origType: origType,
                    data: data,
                    handler: handler,
                    guid: handler.guid,
                    selector: selector,
                    needsContext: selector && jQuery.expr.match.needsContext.test(selector),
                    namespace: namespaces.join(".")
                }, handleObjIn);
                if (!(handlers = events[type])) {
                    handlers = events[type] = [];
                    handlers.delegateCount = 0;
                    if (!special.setup || special.setup.call(elem, data, namespaces, eventHandle) === false) {
                        if (elem.addEventListener) {
                            elem.addEventListener(type, eventHandle, false);
                        }
                    }
                }
                if (special.add) {
                    special.add.call(elem, handleObj);
                    if (!handleObj.handler.guid) {
                        handleObj.handler.guid = handler.guid;
                    }
                }
                if (selector) {
                    handlers.splice(handlers.delegateCount++, 0, handleObj);
                } else {
                    handlers.push(handleObj);
                }
                jQuery.event.global[type] = true;
            }
        },
        remove: function(elem, types, handler, selector, mappedTypes) {
            var j, origCount, tmp, events, t, handleObj, special, handlers, type, namespaces, origType, elemData = data_priv.hasData(elem) && data_priv.get(elem);
            if (!elemData || !(events = elemData.events)) {
                return;
            }
            types = (types || "").match(rnotwhite) || [ "" ];
            t = types.length;
            while (t--) {
                tmp = rtypenamespace.exec(types[t]) || [];
                type = origType = tmp[1];
                namespaces = (tmp[2] || "").split(".").sort();
                if (!type) {
                    for (type in events) {
                        jQuery.event.remove(elem, type + types[t], handler, selector, true);
                    }
                    continue;
                }
                special = jQuery.event.special[type] || {};
                type = (selector ? special.delegateType : special.bindType) || type;
                handlers = events[type] || [];
                tmp = tmp[2] && new RegExp("(^|\\.)" + namespaces.join("\\.(?:.*\\.|)") + "(\\.|$)");
                origCount = j = handlers.length;
                while (j--) {
                    handleObj = handlers[j];
                    if ((mappedTypes || origType === handleObj.origType) && (!handler || handler.guid === handleObj.guid) && (!tmp || tmp.test(handleObj.namespace)) && (!selector || selector === handleObj.selector || selector === "**" && handleObj.selector)) {
                        handlers.splice(j, 1);
                        if (handleObj.selector) {
                            handlers.delegateCount--;
                        }
                        if (special.remove) {
                            special.remove.call(elem, handleObj);
                        }
                    }
                }
                if (origCount && !handlers.length) {
                    if (!special.teardown || special.teardown.call(elem, namespaces, elemData.handle) === false) {
                        jQuery.removeEvent(elem, type, elemData.handle);
                    }
                    delete events[type];
                }
            }
            if (jQuery.isEmptyObject(events)) {
                delete elemData.handle;
                data_priv.remove(elem, "events");
            }
        },
        trigger: function(event, data, elem, onlyHandlers) {
            var i, cur, tmp, bubbleType, ontype, handle, special, eventPath = [ elem || document ], type = hasOwn.call(event, "type") ? event.type : event, namespaces = hasOwn.call(event, "namespace") ? event.namespace.split(".") : [];
            cur = tmp = elem = elem || document;
            if (elem.nodeType === 3 || elem.nodeType === 8) {
                return;
            }
            if (rfocusMorph.test(type + jQuery.event.triggered)) {
                return;
            }
            if (type.indexOf(".") >= 0) {
                namespaces = type.split(".");
                type = namespaces.shift();
                namespaces.sort();
            }
            ontype = type.indexOf(":") < 0 && "on" + type;
            event = event[jQuery.expando] ? event : new jQuery.Event(type, typeof event === "object" && event);
            event.isTrigger = onlyHandlers ? 2 : 3;
            event.namespace = namespaces.join(".");
            event.namespace_re = event.namespace ? new RegExp("(^|\\.)" + namespaces.join("\\.(?:.*\\.|)") + "(\\.|$)") : null;
            event.result = undefined;
            if (!event.target) {
                event.target = elem;
            }
            data = data == null ? [ event ] : jQuery.makeArray(data, [ event ]);
            special = jQuery.event.special[type] || {};
            if (!onlyHandlers && special.trigger && special.trigger.apply(elem, data) === false) {
                return;
            }
            if (!onlyHandlers && !special.noBubble && !jQuery.isWindow(elem)) {
                bubbleType = special.delegateType || type;
                if (!rfocusMorph.test(bubbleType + type)) {
                    cur = cur.parentNode;
                }
                for (;cur; cur = cur.parentNode) {
                    eventPath.push(cur);
                    tmp = cur;
                }
                if (tmp === (elem.ownerDocument || document)) {
                    eventPath.push(tmp.defaultView || tmp.parentWindow || window);
                }
            }
            i = 0;
            while ((cur = eventPath[i++]) && !event.isPropagationStopped()) {
                event.type = i > 1 ? bubbleType : special.bindType || type;
                handle = (data_priv.get(cur, "events") || {})[event.type] && data_priv.get(cur, "handle");
                if (handle) {
                    handle.apply(cur, data);
                }
                handle = ontype && cur[ontype];
                if (handle && handle.apply && jQuery.acceptData(cur)) {
                    event.result = handle.apply(cur, data);
                    if (event.result === false) {
                        event.preventDefault();
                    }
                }
            }
            event.type = type;
            if (!onlyHandlers && !event.isDefaultPrevented()) {
                if ((!special._default || special._default.apply(eventPath.pop(), data) === false) && jQuery.acceptData(elem)) {
                    if (ontype && jQuery.isFunction(elem[type]) && !jQuery.isWindow(elem)) {
                        tmp = elem[ontype];
                        if (tmp) {
                            elem[ontype] = null;
                        }
                        jQuery.event.triggered = type;
                        elem[type]();
                        jQuery.event.triggered = undefined;
                        if (tmp) {
                            elem[ontype] = tmp;
                        }
                    }
                }
            }
            return event.result;
        },
        dispatch: function(event) {
            event = jQuery.event.fix(event);
            var i, j, ret, matched, handleObj, handlerQueue = [], args = slice.call(arguments), handlers = (data_priv.get(this, "events") || {})[event.type] || [], special = jQuery.event.special[event.type] || {};
            args[0] = event;
            event.delegateTarget = this;
            if (special.preDispatch && special.preDispatch.call(this, event) === false) {
                return;
            }
            handlerQueue = jQuery.event.handlers.call(this, event, handlers);
            i = 0;
            while ((matched = handlerQueue[i++]) && !event.isPropagationStopped()) {
                event.currentTarget = matched.elem;
                j = 0;
                while ((handleObj = matched.handlers[j++]) && !event.isImmediatePropagationStopped()) {
                    if (!event.namespace_re || event.namespace_re.test(handleObj.namespace)) {
                        event.handleObj = handleObj;
                        event.data = handleObj.data;
                        ret = ((jQuery.event.special[handleObj.origType] || {}).handle || handleObj.handler).apply(matched.elem, args);
                        if (ret !== undefined) {
                            if ((event.result = ret) === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                        }
                    }
                }
            }
            if (special.postDispatch) {
                special.postDispatch.call(this, event);
            }
            return event.result;
        },
        handlers: function(event, handlers) {
            var i, matches, sel, handleObj, handlerQueue = [], delegateCount = handlers.delegateCount, cur = event.target;
            if (delegateCount && cur.nodeType && (!event.button || event.type !== "click")) {
                for (;cur !== this; cur = cur.parentNode || this) {
                    if (cur.disabled !== true || event.type !== "click") {
                        matches = [];
                        for (i = 0; i < delegateCount; i++) {
                            handleObj = handlers[i];
                            sel = handleObj.selector + " ";
                            if (matches[sel] === undefined) {
                                matches[sel] = handleObj.needsContext ? jQuery(sel, this).index(cur) >= 0 : jQuery.find(sel, this, null, [ cur ]).length;
                            }
                            if (matches[sel]) {
                                matches.push(handleObj);
                            }
                        }
                        if (matches.length) {
                            handlerQueue.push({
                                elem: cur,
                                handlers: matches
                            });
                        }
                    }
                }
            }
            if (delegateCount < handlers.length) {
                handlerQueue.push({
                    elem: this,
                    handlers: handlers.slice(delegateCount)
                });
            }
            return handlerQueue;
        },
        props: "altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),
        fixHooks: {},
        keyHooks: {
            props: "char charCode key keyCode".split(" "),
            filter: function(event, original) {
                if (event.which == null) {
                    event.which = original.charCode != null ? original.charCode : original.keyCode;
                }
                return event;
            }
        },
        mouseHooks: {
            props: "button buttons clientX clientY offsetX offsetY pageX pageY screenX screenY toElement".split(" "),
            filter: function(event, original) {
                var eventDoc, doc, body, button = original.button;
                if (event.pageX == null && original.clientX != null) {
                    eventDoc = event.target.ownerDocument || document;
                    doc = eventDoc.documentElement;
                    body = eventDoc.body;
                    event.pageX = original.clientX + (doc && doc.scrollLeft || body && body.scrollLeft || 0) - (doc && doc.clientLeft || body && body.clientLeft || 0);
                    event.pageY = original.clientY + (doc && doc.scrollTop || body && body.scrollTop || 0) - (doc && doc.clientTop || body && body.clientTop || 0);
                }
                if (!event.which && button !== undefined) {
                    event.which = button & 1 ? 1 : button & 2 ? 3 : button & 4 ? 2 : 0;
                }
                return event;
            }
        },
        fix: function(event) {
            if (event[jQuery.expando]) {
                return event;
            }
            var i, prop, copy, type = event.type, originalEvent = event, fixHook = this.fixHooks[type];
            if (!fixHook) {
                this.fixHooks[type] = fixHook = rmouseEvent.test(type) ? this.mouseHooks : rkeyEvent.test(type) ? this.keyHooks : {};
            }
            copy = fixHook.props ? this.props.concat(fixHook.props) : this.props;
            event = new jQuery.Event(originalEvent);
            i = copy.length;
            while (i--) {
                prop = copy[i];
                event[prop] = originalEvent[prop];
            }
            if (!event.target) {
                event.target = document;
            }
            if (event.target.nodeType === 3) {
                event.target = event.target.parentNode;
            }
            return fixHook.filter ? fixHook.filter(event, originalEvent) : event;
        },
        special: {
            load: {
                noBubble: true
            },
            focus: {
                trigger: function() {
                    if (this !== safeActiveElement() && this.focus) {
                        this.focus();
                        return false;
                    }
                },
                delegateType: "focusin"
            },
            blur: {
                trigger: function() {
                    if (this === safeActiveElement() && this.blur) {
                        this.blur();
                        return false;
                    }
                },
                delegateType: "focusout"
            },
            click: {
                trigger: function() {
                    if (this.type === "checkbox" && this.click && jQuery.nodeName(this, "input")) {
                        this.click();
                        return false;
                    }
                },
                _default: function(event) {
                    return jQuery.nodeName(event.target, "a");
                }
            },
            beforeunload: {
                postDispatch: function(event) {
                    if (event.result !== undefined && event.originalEvent) {
                        event.originalEvent.returnValue = event.result;
                    }
                }
            }
        },
        simulate: function(type, elem, event, bubble) {
            var e = jQuery.extend(new jQuery.Event(), event, {
                type: type,
                isSimulated: true,
                originalEvent: {}
            });
            if (bubble) {
                jQuery.event.trigger(e, null, elem);
            } else {
                jQuery.event.dispatch.call(elem, e);
            }
            if (e.isDefaultPrevented()) {
                event.preventDefault();
            }
        }
    };
    jQuery.removeEvent = function(elem, type, handle) {
        if (elem.removeEventListener) {
            elem.removeEventListener(type, handle, false);
        }
    };
    jQuery.Event = function(src, props) {
        if (!(this instanceof jQuery.Event)) {
            return new jQuery.Event(src, props);
        }
        if (src && src.type) {
            this.originalEvent = src;
            this.type = src.type;
            this.isDefaultPrevented = src.defaultPrevented || src.defaultPrevented === undefined && src.returnValue === false ? returnTrue : returnFalse;
        } else {
            this.type = src;
        }
        if (props) {
            jQuery.extend(this, props);
        }
        this.timeStamp = src && src.timeStamp || jQuery.now();
        this[jQuery.expando] = true;
    };
    jQuery.Event.prototype = {
        isDefaultPrevented: returnFalse,
        isPropagationStopped: returnFalse,
        isImmediatePropagationStopped: returnFalse,
        preventDefault: function() {
            var e = this.originalEvent;
            this.isDefaultPrevented = returnTrue;
            if (e && e.preventDefault) {
                e.preventDefault();
            }
        },
        stopPropagation: function() {
            var e = this.originalEvent;
            this.isPropagationStopped = returnTrue;
            if (e && e.stopPropagation) {
                e.stopPropagation();
            }
        },
        stopImmediatePropagation: function() {
            var e = this.originalEvent;
            this.isImmediatePropagationStopped = returnTrue;
            if (e && e.stopImmediatePropagation) {
                e.stopImmediatePropagation();
            }
            this.stopPropagation();
        }
    };
    jQuery.each({
        mouseenter: "mouseover",
        mouseleave: "mouseout",
        pointerenter: "pointerover",
        pointerleave: "pointerout"
    }, function(orig, fix) {
        jQuery.event.special[orig] = {
            delegateType: fix,
            bindType: fix,
            handle: function(event) {
                var ret, target = this, related = event.relatedTarget, handleObj = event.handleObj;
                if (!related || related !== target && !jQuery.contains(target, related)) {
                    event.type = handleObj.origType;
                    ret = handleObj.handler.apply(this, arguments);
                    event.type = fix;
                }
                return ret;
            }
        };
    });
    if (!support.focusinBubbles) {
        jQuery.each({
            focus: "focusin",
            blur: "focusout"
        }, function(orig, fix) {
            var handler = function(event) {
                jQuery.event.simulate(fix, event.target, jQuery.event.fix(event), true);
            };
            jQuery.event.special[fix] = {
                setup: function() {
                    var doc = this.ownerDocument || this, attaches = data_priv.access(doc, fix);
                    if (!attaches) {
                        doc.addEventListener(orig, handler, true);
                    }
                    data_priv.access(doc, fix, (attaches || 0) + 1);
                },
                teardown: function() {
                    var doc = this.ownerDocument || this, attaches = data_priv.access(doc, fix) - 1;
                    if (!attaches) {
                        doc.removeEventListener(orig, handler, true);
                        data_priv.remove(doc, fix);
                    } else {
                        data_priv.access(doc, fix, attaches);
                    }
                }
            };
        });
    }
    jQuery.fn.extend({
        on: function(types, selector, data, fn, one) {
            var origFn, type;
            if (typeof types === "object") {
                if (typeof selector !== "string") {
                    data = data || selector;
                    selector = undefined;
                }
                for (type in types) {
                    this.on(type, selector, data, types[type], one);
                }
                return this;
            }
            if (data == null && fn == null) {
                fn = selector;
                data = selector = undefined;
            } else if (fn == null) {
                if (typeof selector === "string") {
                    fn = data;
                    data = undefined;
                } else {
                    fn = data;
                    data = selector;
                    selector = undefined;
                }
            }
            if (fn === false) {
                fn = returnFalse;
            } else if (!fn) {
                return this;
            }
            if (one === 1) {
                origFn = fn;
                fn = function(event) {
                    jQuery().off(event);
                    return origFn.apply(this, arguments);
                };
                fn.guid = origFn.guid || (origFn.guid = jQuery.guid++);
            }
            return this.each(function() {
                jQuery.event.add(this, types, fn, data, selector);
            });
        },
        one: function(types, selector, data, fn) {
            return this.on(types, selector, data, fn, 1);
        },
        off: function(types, selector, fn) {
            var handleObj, type;
            if (types && types.preventDefault && types.handleObj) {
                handleObj = types.handleObj;
                jQuery(types.delegateTarget).off(handleObj.namespace ? handleObj.origType + "." + handleObj.namespace : handleObj.origType, handleObj.selector, handleObj.handler);
                return this;
            }
            if (typeof types === "object") {
                for (type in types) {
                    this.off(type, selector, types[type]);
                }
                return this;
            }
            if (selector === false || typeof selector === "function") {
                fn = selector;
                selector = undefined;
            }
            if (fn === false) {
                fn = returnFalse;
            }
            return this.each(function() {
                jQuery.event.remove(this, types, fn, selector);
            });
        },
        trigger: function(type, data) {
            return this.each(function() {
                jQuery.event.trigger(type, data, this);
            });
        },
        triggerHandler: function(type, data) {
            var elem = this[0];
            if (elem) {
                return jQuery.event.trigger(type, data, elem, true);
            }
        }
    });
    var rxhtmlTag = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi, rtagName = /<([\w:]+)/, rhtml = /<|&#?\w+;/, rnoInnerhtml = /<(?:script|style|link)/i, rchecked = /checked\s*(?:[^=]|=\s*.checked.)/i, rscriptType = /^$|\/(?:java|ecma)script/i, rscriptTypeMasked = /^true\/(.*)/, rcleanScript = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g, wrapMap = {
        option: [ 1, "<select multiple='multiple'>", "</select>" ],
        thead: [ 1, "<table>", "</table>" ],
        col: [ 2, "<table><colgroup>", "</colgroup></table>" ],
        tr: [ 2, "<table><tbody>", "</tbody></table>" ],
        td: [ 3, "<table><tbody><tr>", "</tr></tbody></table>" ],
        _default: [ 0, "", "" ]
    };
    wrapMap.optgroup = wrapMap.option;
    wrapMap.tbody = wrapMap.tfoot = wrapMap.colgroup = wrapMap.caption = wrapMap.thead;
    wrapMap.th = wrapMap.td;
    function manipulationTarget(elem, content) {
        return jQuery.nodeName(elem, "table") && jQuery.nodeName(content.nodeType !== 11 ? content : content.firstChild, "tr") ? elem.getElementsByTagName("tbody")[0] || elem.appendChild(elem.ownerDocument.createElement("tbody")) : elem;
    }
    function disableScript(elem) {
        elem.type = (elem.getAttribute("type") !== null) + "/" + elem.type;
        return elem;
    }
    function restoreScript(elem) {
        var match = rscriptTypeMasked.exec(elem.type);
        if (match) {
            elem.type = match[1];
        } else {
            elem.removeAttribute("type");
        }
        return elem;
    }
    function setGlobalEval(elems, refElements) {
        var i = 0, l = elems.length;
        for (;i < l; i++) {
            data_priv.set(elems[i], "globalEval", !refElements || data_priv.get(refElements[i], "globalEval"));
        }
    }
    function cloneCopyEvent(src, dest) {
        var i, l, type, pdataOld, pdataCur, udataOld, udataCur, events;
        if (dest.nodeType !== 1) {
            return;
        }
        if (data_priv.hasData(src)) {
            pdataOld = data_priv.access(src);
            pdataCur = data_priv.set(dest, pdataOld);
            events = pdataOld.events;
            if (events) {
                delete pdataCur.handle;
                pdataCur.events = {};
                for (type in events) {
                    for (i = 0, l = events[type].length; i < l; i++) {
                        jQuery.event.add(dest, type, events[type][i]);
                    }
                }
            }
        }
        if (data_user.hasData(src)) {
            udataOld = data_user.access(src);
            udataCur = jQuery.extend({}, udataOld);
            data_user.set(dest, udataCur);
        }
    }
    function getAll(context, tag) {
        var ret = context.getElementsByTagName ? context.getElementsByTagName(tag || "*") : context.querySelectorAll ? context.querySelectorAll(tag || "*") : [];
        return tag === undefined || tag && jQuery.nodeName(context, tag) ? jQuery.merge([ context ], ret) : ret;
    }
    function fixInput(src, dest) {
        var nodeName = dest.nodeName.toLowerCase();
        if (nodeName === "input" && rcheckableType.test(src.type)) {
            dest.checked = src.checked;
        } else if (nodeName === "input" || nodeName === "textarea") {
            dest.defaultValue = src.defaultValue;
        }
    }
    jQuery.extend({
        clone: function(elem, dataAndEvents, deepDataAndEvents) {
            var i, l, srcElements, destElements, clone = elem.cloneNode(true), inPage = jQuery.contains(elem.ownerDocument, elem);
            if (!support.noCloneChecked && (elem.nodeType === 1 || elem.nodeType === 11) && !jQuery.isXMLDoc(elem)) {
                destElements = getAll(clone);
                srcElements = getAll(elem);
                for (i = 0, l = srcElements.length; i < l; i++) {
                    fixInput(srcElements[i], destElements[i]);
                }
            }
            if (dataAndEvents) {
                if (deepDataAndEvents) {
                    srcElements = srcElements || getAll(elem);
                    destElements = destElements || getAll(clone);
                    for (i = 0, l = srcElements.length; i < l; i++) {
                        cloneCopyEvent(srcElements[i], destElements[i]);
                    }
                } else {
                    cloneCopyEvent(elem, clone);
                }
            }
            destElements = getAll(clone, "script");
            if (destElements.length > 0) {
                setGlobalEval(destElements, !inPage && getAll(elem, "script"));
            }
            return clone;
        },
        buildFragment: function(elems, context, scripts, selection) {
            var elem, tmp, tag, wrap, contains, j, fragment = context.createDocumentFragment(), nodes = [], i = 0, l = elems.length;
            for (;i < l; i++) {
                elem = elems[i];
                if (elem || elem === 0) {
                    if (jQuery.type(elem) === "object") {
                        jQuery.merge(nodes, elem.nodeType ? [ elem ] : elem);
                    } else if (!rhtml.test(elem)) {
                        nodes.push(context.createTextNode(elem));
                    } else {
                        tmp = tmp || fragment.appendChild(context.createElement("div"));
                        tag = (rtagName.exec(elem) || [ "", "" ])[1].toLowerCase();
                        wrap = wrapMap[tag] || wrapMap._default;
                        tmp.innerHTML = wrap[1] + elem.replace(rxhtmlTag, "<$1></$2>") + wrap[2];
                        j = wrap[0];
                        while (j--) {
                            tmp = tmp.lastChild;
                        }
                        jQuery.merge(nodes, tmp.childNodes);
                        tmp = fragment.firstChild;
                        tmp.textContent = "";
                    }
                }
            }
            fragment.textContent = "";
            i = 0;
            while (elem = nodes[i++]) {
                if (selection && jQuery.inArray(elem, selection) !== -1) {
                    continue;
                }
                contains = jQuery.contains(elem.ownerDocument, elem);
                tmp = getAll(fragment.appendChild(elem), "script");
                if (contains) {
                    setGlobalEval(tmp);
                }
                if (scripts) {
                    j = 0;
                    while (elem = tmp[j++]) {
                        if (rscriptType.test(elem.type || "")) {
                            scripts.push(elem);
                        }
                    }
                }
            }
            return fragment;
        },
        cleanData: function(elems) {
            var data, elem, type, key, special = jQuery.event.special, i = 0;
            for (;(elem = elems[i]) !== undefined; i++) {
                if (jQuery.acceptData(elem)) {
                    key = elem[data_priv.expando];
                    if (key && (data = data_priv.cache[key])) {
                        if (data.events) {
                            for (type in data.events) {
                                if (special[type]) {
                                    jQuery.event.remove(elem, type);
                                } else {
                                    jQuery.removeEvent(elem, type, data.handle);
                                }
                            }
                        }
                        if (data_priv.cache[key]) {
                            delete data_priv.cache[key];
                        }
                    }
                }
                delete data_user.cache[elem[data_user.expando]];
            }
        }
    });
    jQuery.fn.extend({
        text: function(value) {
            return access(this, function(value) {
                return value === undefined ? jQuery.text(this) : this.empty().each(function() {
                    if (this.nodeType === 1 || this.nodeType === 11 || this.nodeType === 9) {
                        this.textContent = value;
                    }
                });
            }, null, value, arguments.length);
        },
        append: function() {
            return this.domManip(arguments, function(elem) {
                if (this.nodeType === 1 || this.nodeType === 11 || this.nodeType === 9) {
                    var target = manipulationTarget(this, elem);
                    target.appendChild(elem);
                }
            });
        },
        prepend: function() {
            return this.domManip(arguments, function(elem) {
                if (this.nodeType === 1 || this.nodeType === 11 || this.nodeType === 9) {
                    var target = manipulationTarget(this, elem);
                    target.insertBefore(elem, target.firstChild);
                }
            });
        },
        before: function() {
            return this.domManip(arguments, function(elem) {
                if (this.parentNode) {
                    this.parentNode.insertBefore(elem, this);
                }
            });
        },
        after: function() {
            return this.domManip(arguments, function(elem) {
                if (this.parentNode) {
                    this.parentNode.insertBefore(elem, this.nextSibling);
                }
            });
        },
        remove: function(selector, keepData) {
            var elem, elems = selector ? jQuery.filter(selector, this) : this, i = 0;
            for (;(elem = elems[i]) != null; i++) {
                if (!keepData && elem.nodeType === 1) {
                    jQuery.cleanData(getAll(elem));
                }
                if (elem.parentNode) {
                    if (keepData && jQuery.contains(elem.ownerDocument, elem)) {
                        setGlobalEval(getAll(elem, "script"));
                    }
                    elem.parentNode.removeChild(elem);
                }
            }
            return this;
        },
        empty: function() {
            var elem, i = 0;
            for (;(elem = this[i]) != null; i++) {
                if (elem.nodeType === 1) {
                    jQuery.cleanData(getAll(elem, false));
                    elem.textContent = "";
                }
            }
            return this;
        },
        clone: function(dataAndEvents, deepDataAndEvents) {
            dataAndEvents = dataAndEvents == null ? false : dataAndEvents;
            deepDataAndEvents = deepDataAndEvents == null ? dataAndEvents : deepDataAndEvents;
            return this.map(function() {
                return jQuery.clone(this, dataAndEvents, deepDataAndEvents);
            });
        },
        html: function(value) {
            return access(this, function(value) {
                var elem = this[0] || {}, i = 0, l = this.length;
                if (value === undefined && elem.nodeType === 1) {
                    return elem.innerHTML;
                }
                if (typeof value === "string" && !rnoInnerhtml.test(value) && !wrapMap[(rtagName.exec(value) || [ "", "" ])[1].toLowerCase()]) {
                    value = value.replace(rxhtmlTag, "<$1></$2>");
                    try {
                        for (;i < l; i++) {
                            elem = this[i] || {};
                            if (elem.nodeType === 1) {
                                jQuery.cleanData(getAll(elem, false));
                                elem.innerHTML = value;
                            }
                        }
                        elem = 0;
                    } catch (e) {}
                }
                if (elem) {
                    this.empty().append(value);
                }
            }, null, value, arguments.length);
        },
        replaceWith: function() {
            var arg = arguments[0];
            this.domManip(arguments, function(elem) {
                arg = this.parentNode;
                jQuery.cleanData(getAll(this));
                if (arg) {
                    arg.replaceChild(elem, this);
                }
            });
            return arg && (arg.length || arg.nodeType) ? this : this.remove();
        },
        detach: function(selector) {
            return this.remove(selector, true);
        },
        domManip: function(args, callback) {
            args = concat.apply([], args);
            var fragment, first, scripts, hasScripts, node, doc, i = 0, l = this.length, set = this, iNoClone = l - 1, value = args[0], isFunction = jQuery.isFunction(value);
            if (isFunction || l > 1 && typeof value === "string" && !support.checkClone && rchecked.test(value)) {
                return this.each(function(index) {
                    var self = set.eq(index);
                    if (isFunction) {
                        args[0] = value.call(this, index, self.html());
                    }
                    self.domManip(args, callback);
                });
            }
            if (l) {
                fragment = jQuery.buildFragment(args, this[0].ownerDocument, false, this);
                first = fragment.firstChild;
                if (fragment.childNodes.length === 1) {
                    fragment = first;
                }
                if (first) {
                    scripts = jQuery.map(getAll(fragment, "script"), disableScript);
                    hasScripts = scripts.length;
                    for (;i < l; i++) {
                        node = fragment;
                        if (i !== iNoClone) {
                            node = jQuery.clone(node, true, true);
                            if (hasScripts) {
                                jQuery.merge(scripts, getAll(node, "script"));
                            }
                        }
                        callback.call(this[i], node, i);
                    }
                    if (hasScripts) {
                        doc = scripts[scripts.length - 1].ownerDocument;
                        jQuery.map(scripts, restoreScript);
                        for (i = 0; i < hasScripts; i++) {
                            node = scripts[i];
                            if (rscriptType.test(node.type || "") && !data_priv.access(node, "globalEval") && jQuery.contains(doc, node)) {
                                if (node.src) {
                                    if (jQuery._evalUrl) {
                                        jQuery._evalUrl(node.src);
                                    }
                                } else {
                                    jQuery.globalEval(node.textContent.replace(rcleanScript, ""));
                                }
                            }
                        }
                    }
                }
            }
            return this;
        }
    });
    jQuery.each({
        appendTo: "append",
        prependTo: "prepend",
        insertBefore: "before",
        insertAfter: "after",
        replaceAll: "replaceWith"
    }, function(name, original) {
        jQuery.fn[name] = function(selector) {
            var elems, ret = [], insert = jQuery(selector), last = insert.length - 1, i = 0;
            for (;i <= last; i++) {
                elems = i === last ? this : this.clone(true);
                jQuery(insert[i])[original](elems);
                push.apply(ret, elems.get());
            }
            return this.pushStack(ret);
        };
    });
    var iframe, elemdisplay = {};
    function actualDisplay(name, doc) {
        var style, elem = jQuery(doc.createElement(name)).appendTo(doc.body), display = window.getDefaultComputedStyle && (style = window.getDefaultComputedStyle(elem[0])) ? style.display : jQuery.css(elem[0], "display");
        elem.detach();
        return display;
    }
    function defaultDisplay(nodeName) {
        var doc = document, display = elemdisplay[nodeName];
        if (!display) {
            display = actualDisplay(nodeName, doc);
            if (display === "none" || !display) {
                iframe = (iframe || jQuery("<iframe frameborder='0' width='0' height='0'/>")).appendTo(doc.documentElement);
                doc = iframe[0].contentDocument;
                doc.write();
                doc.close();
                display = actualDisplay(nodeName, doc);
                iframe.detach();
            }
            elemdisplay[nodeName] = display;
        }
        return display;
    }
    var rmargin = /^margin/;
    var rnumnonpx = new RegExp("^(" + pnum + ")(?!px)[a-z%]+$", "i");
    var getStyles = function(elem) {
        if (elem.ownerDocument.defaultView.opener) {
            return elem.ownerDocument.defaultView.getComputedStyle(elem, null);
        }
        return window.getComputedStyle(elem, null);
    };
    function curCSS(elem, name, computed) {
        var width, minWidth, maxWidth, ret, style = elem.style;
        computed = computed || getStyles(elem);
        if (computed) {
            ret = computed.getPropertyValue(name) || computed[name];
        }
        if (computed) {
            if (ret === "" && !jQuery.contains(elem.ownerDocument, elem)) {
                ret = jQuery.style(elem, name);
            }
            if (rnumnonpx.test(ret) && rmargin.test(name)) {
                width = style.width;
                minWidth = style.minWidth;
                maxWidth = style.maxWidth;
                style.minWidth = style.maxWidth = style.width = ret;
                ret = computed.width;
                style.width = width;
                style.minWidth = minWidth;
                style.maxWidth = maxWidth;
            }
        }
        return ret !== undefined ? ret + "" : ret;
    }
    function addGetHookIf(conditionFn, hookFn) {
        return {
            get: function() {
                if (conditionFn()) {
                    delete this.get;
                    return;
                }
                return (this.get = hookFn).apply(this, arguments);
            }
        };
    }
    (function() {
        var pixelPositionVal, boxSizingReliableVal, docElem = document.documentElement, container = document.createElement("div"), div = document.createElement("div");
        if (!div.style) {
            return;
        }
        div.style.backgroundClip = "content-box";
        div.cloneNode(true).style.backgroundClip = "";
        support.clearCloneStyle = div.style.backgroundClip === "content-box";
        container.style.cssText = "border:0;width:0;height:0;top:0;left:-9999px;margin-top:1px;" + "position:absolute";
        container.appendChild(div);
        function computePixelPositionAndBoxSizingReliable() {
            div.style.cssText = "-webkit-box-sizing:border-box;-moz-box-sizing:border-box;" + "box-sizing:border-box;display:block;margin-top:1%;top:1%;" + "border:1px;padding:1px;width:4px;position:absolute";
            div.innerHTML = "";
            docElem.appendChild(container);
            var divStyle = window.getComputedStyle(div, null);
            pixelPositionVal = divStyle.top !== "1%";
            boxSizingReliableVal = divStyle.width === "4px";
            docElem.removeChild(container);
        }
        if (window.getComputedStyle) {
            jQuery.extend(support, {
                pixelPosition: function() {
                    computePixelPositionAndBoxSizingReliable();
                    return pixelPositionVal;
                },
                boxSizingReliable: function() {
                    if (boxSizingReliableVal == null) {
                        computePixelPositionAndBoxSizingReliable();
                    }
                    return boxSizingReliableVal;
                },
                reliableMarginRight: function() {
                    var ret, marginDiv = div.appendChild(document.createElement("div"));
                    marginDiv.style.cssText = div.style.cssText = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;" + "box-sizing:content-box;display:block;margin:0;border:0;padding:0";
                    marginDiv.style.marginRight = marginDiv.style.width = "0";
                    div.style.width = "1px";
                    docElem.appendChild(container);
                    ret = !parseFloat(window.getComputedStyle(marginDiv, null).marginRight);
                    docElem.removeChild(container);
                    div.removeChild(marginDiv);
                    return ret;
                }
            });
        }
    })();
    jQuery.swap = function(elem, options, callback, args) {
        var ret, name, old = {};
        for (name in options) {
            old[name] = elem.style[name];
            elem.style[name] = options[name];
        }
        ret = callback.apply(elem, args || []);
        for (name in options) {
            elem.style[name] = old[name];
        }
        return ret;
    };
    var rdisplayswap = /^(none|table(?!-c[ea]).+)/, rnumsplit = new RegExp("^(" + pnum + ")(.*)$", "i"), rrelNum = new RegExp("^([+-])=(" + pnum + ")", "i"), cssShow = {
        position: "absolute",
        visibility: "hidden",
        display: "block"
    }, cssNormalTransform = {
        letterSpacing: "0",
        fontWeight: "400"
    }, cssPrefixes = [ "Webkit", "O", "Moz", "ms" ];
    function vendorPropName(style, name) {
        if (name in style) {
            return name;
        }
        var capName = name[0].toUpperCase() + name.slice(1), origName = name, i = cssPrefixes.length;
        while (i--) {
            name = cssPrefixes[i] + capName;
            if (name in style) {
                return name;
            }
        }
        return origName;
    }
    function setPositiveNumber(elem, value, subtract) {
        var matches = rnumsplit.exec(value);
        return matches ? Math.max(0, matches[1] - (subtract || 0)) + (matches[2] || "px") : value;
    }
    function augmentWidthOrHeight(elem, name, extra, isBorderBox, styles) {
        var i = extra === (isBorderBox ? "border" : "content") ? 4 : name === "width" ? 1 : 0, val = 0;
        for (;i < 4; i += 2) {
            if (extra === "margin") {
                val += jQuery.css(elem, extra + cssExpand[i], true, styles);
            }
            if (isBorderBox) {
                if (extra === "content") {
                    val -= jQuery.css(elem, "padding" + cssExpand[i], true, styles);
                }
                if (extra !== "margin") {
                    val -= jQuery.css(elem, "border" + cssExpand[i] + "Width", true, styles);
                }
            } else {
                val += jQuery.css(elem, "padding" + cssExpand[i], true, styles);
                if (extra !== "padding") {
                    val += jQuery.css(elem, "border" + cssExpand[i] + "Width", true, styles);
                }
            }
        }
        return val;
    }
    function getWidthOrHeight(elem, name, extra) {
        var valueIsBorderBox = true, val = name === "width" ? elem.offsetWidth : elem.offsetHeight, styles = getStyles(elem), isBorderBox = jQuery.css(elem, "boxSizing", false, styles) === "border-box";
        if (val <= 0 || val == null) {
            val = curCSS(elem, name, styles);
            if (val < 0 || val == null) {
                val = elem.style[name];
            }
            if (rnumnonpx.test(val)) {
                return val;
            }
            valueIsBorderBox = isBorderBox && (support.boxSizingReliable() || val === elem.style[name]);
            val = parseFloat(val) || 0;
        }
        return val + augmentWidthOrHeight(elem, name, extra || (isBorderBox ? "border" : "content"), valueIsBorderBox, styles) + "px";
    }
    function showHide(elements, show) {
        var display, elem, hidden, values = [], index = 0, length = elements.length;
        for (;index < length; index++) {
            elem = elements[index];
            if (!elem.style) {
                continue;
            }
            values[index] = data_priv.get(elem, "olddisplay");
            display = elem.style.display;
            if (show) {
                if (!values[index] && display === "none") {
                    elem.style.display = "";
                }
                if (elem.style.display === "" && isHidden(elem)) {
                    values[index] = data_priv.access(elem, "olddisplay", defaultDisplay(elem.nodeName));
                }
            } else {
                hidden = isHidden(elem);
                if (display !== "none" || !hidden) {
                    data_priv.set(elem, "olddisplay", hidden ? display : jQuery.css(elem, "display"));
                }
            }
        }
        for (index = 0; index < length; index++) {
            elem = elements[index];
            if (!elem.style) {
                continue;
            }
            if (!show || elem.style.display === "none" || elem.style.display === "") {
                elem.style.display = show ? values[index] || "" : "none";
            }
        }
        return elements;
    }
    jQuery.extend({
        cssHooks: {
            opacity: {
                get: function(elem, computed) {
                    if (computed) {
                        var ret = curCSS(elem, "opacity");
                        return ret === "" ? "1" : ret;
                    }
                }
            }
        },
        cssNumber: {
            columnCount: true,
            fillOpacity: true,
            flexGrow: true,
            flexShrink: true,
            fontWeight: true,
            lineHeight: true,
            opacity: true,
            order: true,
            orphans: true,
            widows: true,
            zIndex: true,
            zoom: true
        },
        cssProps: {
            "float": "cssFloat"
        },
        style: function(elem, name, value, extra) {
            if (!elem || elem.nodeType === 3 || elem.nodeType === 8 || !elem.style) {
                return;
            }
            var ret, type, hooks, origName = jQuery.camelCase(name), style = elem.style;
            name = jQuery.cssProps[origName] || (jQuery.cssProps[origName] = vendorPropName(style, origName));
            hooks = jQuery.cssHooks[name] || jQuery.cssHooks[origName];
            if (value !== undefined) {
                type = typeof value;
                if (type === "string" && (ret = rrelNum.exec(value))) {
                    value = (ret[1] + 1) * ret[2] + parseFloat(jQuery.css(elem, name));
                    type = "number";
                }
                if (value == null || value !== value) {
                    return;
                }
                if (type === "number" && !jQuery.cssNumber[origName]) {
                    value += "px";
                }
                if (!support.clearCloneStyle && value === "" && name.indexOf("background") === 0) {
                    style[name] = "inherit";
                }
                if (!hooks || !("set" in hooks) || (value = hooks.set(elem, value, extra)) !== undefined) {
                    style[name] = value;
                }
            } else {
                if (hooks && "get" in hooks && (ret = hooks.get(elem, false, extra)) !== undefined) {
                    return ret;
                }
                return style[name];
            }
        },
        css: function(elem, name, extra, styles) {
            var val, num, hooks, origName = jQuery.camelCase(name);
            name = jQuery.cssProps[origName] || (jQuery.cssProps[origName] = vendorPropName(elem.style, origName));
            hooks = jQuery.cssHooks[name] || jQuery.cssHooks[origName];
            if (hooks && "get" in hooks) {
                val = hooks.get(elem, true, extra);
            }
            if (val === undefined) {
                val = curCSS(elem, name, styles);
            }
            if (val === "normal" && name in cssNormalTransform) {
                val = cssNormalTransform[name];
            }
            if (extra === "" || extra) {
                num = parseFloat(val);
                return extra === true || jQuery.isNumeric(num) ? num || 0 : val;
            }
            return val;
        }
    });
    jQuery.each([ "height", "width" ], function(i, name) {
        jQuery.cssHooks[name] = {
            get: function(elem, computed, extra) {
                if (computed) {
                    return rdisplayswap.test(jQuery.css(elem, "display")) && elem.offsetWidth === 0 ? jQuery.swap(elem, cssShow, function() {
                        return getWidthOrHeight(elem, name, extra);
                    }) : getWidthOrHeight(elem, name, extra);
                }
            },
            set: function(elem, value, extra) {
                var styles = extra && getStyles(elem);
                return setPositiveNumber(elem, value, extra ? augmentWidthOrHeight(elem, name, extra, jQuery.css(elem, "boxSizing", false, styles) === "border-box", styles) : 0);
            }
        };
    });
    jQuery.cssHooks.marginRight = addGetHookIf(support.reliableMarginRight, function(elem, computed) {
        if (computed) {
            return jQuery.swap(elem, {
                display: "inline-block"
            }, curCSS, [ elem, "marginRight" ]);
        }
    });
    jQuery.each({
        margin: "",
        padding: "",
        border: "Width"
    }, function(prefix, suffix) {
        jQuery.cssHooks[prefix + suffix] = {
            expand: function(value) {
                var i = 0, expanded = {}, parts = typeof value === "string" ? value.split(" ") : [ value ];
                for (;i < 4; i++) {
                    expanded[prefix + cssExpand[i] + suffix] = parts[i] || parts[i - 2] || parts[0];
                }
                return expanded;
            }
        };
        if (!rmargin.test(prefix)) {
            jQuery.cssHooks[prefix + suffix].set = setPositiveNumber;
        }
    });
    jQuery.fn.extend({
        css: function(name, value) {
            return access(this, function(elem, name, value) {
                var styles, len, map = {}, i = 0;
                if (jQuery.isArray(name)) {
                    styles = getStyles(elem);
                    len = name.length;
                    for (;i < len; i++) {
                        map[name[i]] = jQuery.css(elem, name[i], false, styles);
                    }
                    return map;
                }
                return value !== undefined ? jQuery.style(elem, name, value) : jQuery.css(elem, name);
            }, name, value, arguments.length > 1);
        },
        show: function() {
            return showHide(this, true);
        },
        hide: function() {
            return showHide(this);
        },
        toggle: function(state) {
            if (typeof state === "boolean") {
                return state ? this.show() : this.hide();
            }
            return this.each(function() {
                if (isHidden(this)) {
                    jQuery(this).show();
                } else {
                    jQuery(this).hide();
                }
            });
        }
    });
    function Tween(elem, options, prop, end, easing) {
        return new Tween.prototype.init(elem, options, prop, end, easing);
    }
    jQuery.Tween = Tween;
    Tween.prototype = {
        constructor: Tween,
        init: function(elem, options, prop, end, easing, unit) {
            this.elem = elem;
            this.prop = prop;
            this.easing = easing || "swing";
            this.options = options;
            this.start = this.now = this.cur();
            this.end = end;
            this.unit = unit || (jQuery.cssNumber[prop] ? "" : "px");
        },
        cur: function() {
            var hooks = Tween.propHooks[this.prop];
            return hooks && hooks.get ? hooks.get(this) : Tween.propHooks._default.get(this);
        },
        run: function(percent) {
            var eased, hooks = Tween.propHooks[this.prop];
            if (this.options.duration) {
                this.pos = eased = jQuery.easing[this.easing](percent, this.options.duration * percent, 0, 1, this.options.duration);
            } else {
                this.pos = eased = percent;
            }
            this.now = (this.end - this.start) * eased + this.start;
            if (this.options.step) {
                this.options.step.call(this.elem, this.now, this);
            }
            if (hooks && hooks.set) {
                hooks.set(this);
            } else {
                Tween.propHooks._default.set(this);
            }
            return this;
        }
    };
    Tween.prototype.init.prototype = Tween.prototype;
    Tween.propHooks = {
        _default: {
            get: function(tween) {
                var result;
                if (tween.elem[tween.prop] != null && (!tween.elem.style || tween.elem.style[tween.prop] == null)) {
                    return tween.elem[tween.prop];
                }
                result = jQuery.css(tween.elem, tween.prop, "");
                return !result || result === "auto" ? 0 : result;
            },
            set: function(tween) {
                if (jQuery.fx.step[tween.prop]) {
                    jQuery.fx.step[tween.prop](tween);
                } else if (tween.elem.style && (tween.elem.style[jQuery.cssProps[tween.prop]] != null || jQuery.cssHooks[tween.prop])) {
                    jQuery.style(tween.elem, tween.prop, tween.now + tween.unit);
                } else {
                    tween.elem[tween.prop] = tween.now;
                }
            }
        }
    };
    Tween.propHooks.scrollTop = Tween.propHooks.scrollLeft = {
        set: function(tween) {
            if (tween.elem.nodeType && tween.elem.parentNode) {
                tween.elem[tween.prop] = tween.now;
            }
        }
    };
    jQuery.easing = {
        linear: function(p) {
            return p;
        },
        swing: function(p) {
            return .5 - Math.cos(p * Math.PI) / 2;
        }
    };
    jQuery.fx = Tween.prototype.init;
    jQuery.fx.step = {};
    var fxNow, timerId, rfxtypes = /^(?:toggle|show|hide)$/, rfxnum = new RegExp("^(?:([+-])=|)(" + pnum + ")([a-z%]*)$", "i"), rrun = /queueHooks$/, animationPrefilters = [ defaultPrefilter ], tweeners = {
        "*": [ function(prop, value) {
            var tween = this.createTween(prop, value), target = tween.cur(), parts = rfxnum.exec(value), unit = parts && parts[3] || (jQuery.cssNumber[prop] ? "" : "px"), start = (jQuery.cssNumber[prop] || unit !== "px" && +target) && rfxnum.exec(jQuery.css(tween.elem, prop)), scale = 1, maxIterations = 20;
            if (start && start[3] !== unit) {
                unit = unit || start[3];
                parts = parts || [];
                start = +target || 1;
                do {
                    scale = scale || ".5";
                    start = start / scale;
                    jQuery.style(tween.elem, prop, start + unit);
                } while (scale !== (scale = tween.cur() / target) && scale !== 1 && --maxIterations);
            }
            if (parts) {
                start = tween.start = +start || +target || 0;
                tween.unit = unit;
                tween.end = parts[1] ? start + (parts[1] + 1) * parts[2] : +parts[2];
            }
            return tween;
        } ]
    };
    function createFxNow() {
        setTimeout(function() {
            fxNow = undefined;
        });
        return fxNow = jQuery.now();
    }
    function genFx(type, includeWidth) {
        var which, i = 0, attrs = {
            height: type
        };
        includeWidth = includeWidth ? 1 : 0;
        for (;i < 4; i += 2 - includeWidth) {
            which = cssExpand[i];
            attrs["margin" + which] = attrs["padding" + which] = type;
        }
        if (includeWidth) {
            attrs.opacity = attrs.width = type;
        }
        return attrs;
    }
    function createTween(value, prop, animation) {
        var tween, collection = (tweeners[prop] || []).concat(tweeners["*"]), index = 0, length = collection.length;
        for (;index < length; index++) {
            if (tween = collection[index].call(animation, prop, value)) {
                return tween;
            }
        }
    }
    function defaultPrefilter(elem, props, opts) {
        var prop, value, toggle, tween, hooks, oldfire, display, checkDisplay, anim = this, orig = {}, style = elem.style, hidden = elem.nodeType && isHidden(elem), dataShow = data_priv.get(elem, "fxshow");
        if (!opts.queue) {
            hooks = jQuery._queueHooks(elem, "fx");
            if (hooks.unqueued == null) {
                hooks.unqueued = 0;
                oldfire = hooks.empty.fire;
                hooks.empty.fire = function() {
                    if (!hooks.unqueued) {
                        oldfire();
                    }
                };
            }
            hooks.unqueued++;
            anim.always(function() {
                anim.always(function() {
                    hooks.unqueued--;
                    if (!jQuery.queue(elem, "fx").length) {
                        hooks.empty.fire();
                    }
                });
            });
        }
        if (elem.nodeType === 1 && ("height" in props || "width" in props)) {
            opts.overflow = [ style.overflow, style.overflowX, style.overflowY ];
            display = jQuery.css(elem, "display");
            checkDisplay = display === "none" ? data_priv.get(elem, "olddisplay") || defaultDisplay(elem.nodeName) : display;
            if (checkDisplay === "inline" && jQuery.css(elem, "float") === "none") {
                style.display = "inline-block";
            }
        }
        if (opts.overflow) {
            style.overflow = "hidden";
            anim.always(function() {
                style.overflow = opts.overflow[0];
                style.overflowX = opts.overflow[1];
                style.overflowY = opts.overflow[2];
            });
        }
        for (prop in props) {
            value = props[prop];
            if (rfxtypes.exec(value)) {
                delete props[prop];
                toggle = toggle || value === "toggle";
                if (value === (hidden ? "hide" : "show")) {
                    if (value === "show" && dataShow && dataShow[prop] !== undefined) {
                        hidden = true;
                    } else {
                        continue;
                    }
                }
                orig[prop] = dataShow && dataShow[prop] || jQuery.style(elem, prop);
            } else {
                display = undefined;
            }
        }
        if (!jQuery.isEmptyObject(orig)) {
            if (dataShow) {
                if ("hidden" in dataShow) {
                    hidden = dataShow.hidden;
                }
            } else {
                dataShow = data_priv.access(elem, "fxshow", {});
            }
            if (toggle) {
                dataShow.hidden = !hidden;
            }
            if (hidden) {
                jQuery(elem).show();
            } else {
                anim.done(function() {
                    jQuery(elem).hide();
                });
            }
            anim.done(function() {
                var prop;
                data_priv.remove(elem, "fxshow");
                for (prop in orig) {
                    jQuery.style(elem, prop, orig[prop]);
                }
            });
            for (prop in orig) {
                tween = createTween(hidden ? dataShow[prop] : 0, prop, anim);
                if (!(prop in dataShow)) {
                    dataShow[prop] = tween.start;
                    if (hidden) {
                        tween.end = tween.start;
                        tween.start = prop === "width" || prop === "height" ? 1 : 0;
                    }
                }
            }
        } else if ((display === "none" ? defaultDisplay(elem.nodeName) : display) === "inline") {
            style.display = display;
        }
    }
    function propFilter(props, specialEasing) {
        var index, name, easing, value, hooks;
        for (index in props) {
            name = jQuery.camelCase(index);
            easing = specialEasing[name];
            value = props[index];
            if (jQuery.isArray(value)) {
                easing = value[1];
                value = props[index] = value[0];
            }
            if (index !== name) {
                props[name] = value;
                delete props[index];
            }
            hooks = jQuery.cssHooks[name];
            if (hooks && "expand" in hooks) {
                value = hooks.expand(value);
                delete props[name];
                for (index in value) {
                    if (!(index in props)) {
                        props[index] = value[index];
                        specialEasing[index] = easing;
                    }
                }
            } else {
                specialEasing[name] = easing;
            }
        }
    }
    function Animation(elem, properties, options) {
        var result, stopped, index = 0, length = animationPrefilters.length, deferred = jQuery.Deferred().always(function() {
            delete tick.elem;
        }), tick = function() {
            if (stopped) {
                return false;
            }
            var currentTime = fxNow || createFxNow(), remaining = Math.max(0, animation.startTime + animation.duration - currentTime), temp = remaining / animation.duration || 0, percent = 1 - temp, index = 0, length = animation.tweens.length;
            for (;index < length; index++) {
                animation.tweens[index].run(percent);
            }
            deferred.notifyWith(elem, [ animation, percent, remaining ]);
            if (percent < 1 && length) {
                return remaining;
            } else {
                deferred.resolveWith(elem, [ animation ]);
                return false;
            }
        }, animation = deferred.promise({
            elem: elem,
            props: jQuery.extend({}, properties),
            opts: jQuery.extend(true, {
                specialEasing: {}
            }, options),
            originalProperties: properties,
            originalOptions: options,
            startTime: fxNow || createFxNow(),
            duration: options.duration,
            tweens: [],
            createTween: function(prop, end) {
                var tween = jQuery.Tween(elem, animation.opts, prop, end, animation.opts.specialEasing[prop] || animation.opts.easing);
                animation.tweens.push(tween);
                return tween;
            },
            stop: function(gotoEnd) {
                var index = 0, length = gotoEnd ? animation.tweens.length : 0;
                if (stopped) {
                    return this;
                }
                stopped = true;
                for (;index < length; index++) {
                    animation.tweens[index].run(1);
                }
                if (gotoEnd) {
                    deferred.resolveWith(elem, [ animation, gotoEnd ]);
                } else {
                    deferred.rejectWith(elem, [ animation, gotoEnd ]);
                }
                return this;
            }
        }), props = animation.props;
        propFilter(props, animation.opts.specialEasing);
        for (;index < length; index++) {
            result = animationPrefilters[index].call(animation, elem, props, animation.opts);
            if (result) {
                return result;
            }
        }
        jQuery.map(props, createTween, animation);
        if (jQuery.isFunction(animation.opts.start)) {
            animation.opts.start.call(elem, animation);
        }
        jQuery.fx.timer(jQuery.extend(tick, {
            elem: elem,
            anim: animation,
            queue: animation.opts.queue
        }));
        return animation.progress(animation.opts.progress).done(animation.opts.done, animation.opts.complete).fail(animation.opts.fail).always(animation.opts.always);
    }
    jQuery.Animation = jQuery.extend(Animation, {
        tweener: function(props, callback) {
            if (jQuery.isFunction(props)) {
                callback = props;
                props = [ "*" ];
            } else {
                props = props.split(" ");
            }
            var prop, index = 0, length = props.length;
            for (;index < length; index++) {
                prop = props[index];
                tweeners[prop] = tweeners[prop] || [];
                tweeners[prop].unshift(callback);
            }
        },
        prefilter: function(callback, prepend) {
            if (prepend) {
                animationPrefilters.unshift(callback);
            } else {
                animationPrefilters.push(callback);
            }
        }
    });
    jQuery.speed = function(speed, easing, fn) {
        var opt = speed && typeof speed === "object" ? jQuery.extend({}, speed) : {
            complete: fn || !fn && easing || jQuery.isFunction(speed) && speed,
            duration: speed,
            easing: fn && easing || easing && !jQuery.isFunction(easing) && easing
        };
        opt.duration = jQuery.fx.off ? 0 : typeof opt.duration === "number" ? opt.duration : opt.duration in jQuery.fx.speeds ? jQuery.fx.speeds[opt.duration] : jQuery.fx.speeds._default;
        if (opt.queue == null || opt.queue === true) {
            opt.queue = "fx";
        }
        opt.old = opt.complete;
        opt.complete = function() {
            if (jQuery.isFunction(opt.old)) {
                opt.old.call(this);
            }
            if (opt.queue) {
                jQuery.dequeue(this, opt.queue);
            }
        };
        return opt;
    };
    jQuery.fn.extend({
        fadeTo: function(speed, to, easing, callback) {
            return this.filter(isHidden).css("opacity", 0).show().end().animate({
                opacity: to
            }, speed, easing, callback);
        },
        animate: function(prop, speed, easing, callback) {
            var empty = jQuery.isEmptyObject(prop), optall = jQuery.speed(speed, easing, callback), doAnimation = function() {
                var anim = Animation(this, jQuery.extend({}, prop), optall);
                if (empty || data_priv.get(this, "finish")) {
                    anim.stop(true);
                }
            };
            doAnimation.finish = doAnimation;
            return empty || optall.queue === false ? this.each(doAnimation) : this.queue(optall.queue, doAnimation);
        },
        stop: function(type, clearQueue, gotoEnd) {
            var stopQueue = function(hooks) {
                var stop = hooks.stop;
                delete hooks.stop;
                stop(gotoEnd);
            };
            if (typeof type !== "string") {
                gotoEnd = clearQueue;
                clearQueue = type;
                type = undefined;
            }
            if (clearQueue && type !== false) {
                this.queue(type || "fx", []);
            }
            return this.each(function() {
                var dequeue = true, index = type != null && type + "queueHooks", timers = jQuery.timers, data = data_priv.get(this);
                if (index) {
                    if (data[index] && data[index].stop) {
                        stopQueue(data[index]);
                    }
                } else {
                    for (index in data) {
                        if (data[index] && data[index].stop && rrun.test(index)) {
                            stopQueue(data[index]);
                        }
                    }
                }
                for (index = timers.length; index--; ) {
                    if (timers[index].elem === this && (type == null || timers[index].queue === type)) {
                        timers[index].anim.stop(gotoEnd);
                        dequeue = false;
                        timers.splice(index, 1);
                    }
                }
                if (dequeue || !gotoEnd) {
                    jQuery.dequeue(this, type);
                }
            });
        },
        finish: function(type) {
            if (type !== false) {
                type = type || "fx";
            }
            return this.each(function() {
                var index, data = data_priv.get(this), queue = data[type + "queue"], hooks = data[type + "queueHooks"], timers = jQuery.timers, length = queue ? queue.length : 0;
                data.finish = true;
                jQuery.queue(this, type, []);
                if (hooks && hooks.stop) {
                    hooks.stop.call(this, true);
                }
                for (index = timers.length; index--; ) {
                    if (timers[index].elem === this && timers[index].queue === type) {
                        timers[index].anim.stop(true);
                        timers.splice(index, 1);
                    }
                }
                for (index = 0; index < length; index++) {
                    if (queue[index] && queue[index].finish) {
                        queue[index].finish.call(this);
                    }
                }
                delete data.finish;
            });
        }
    });
    jQuery.each([ "toggle", "show", "hide" ], function(i, name) {
        var cssFn = jQuery.fn[name];
        jQuery.fn[name] = function(speed, easing, callback) {
            return speed == null || typeof speed === "boolean" ? cssFn.apply(this, arguments) : this.animate(genFx(name, true), speed, easing, callback);
        };
    });
    jQuery.each({
        slideDown: genFx("show"),
        slideUp: genFx("hide"),
        slideToggle: genFx("toggle"),
        fadeIn: {
            opacity: "show"
        },
        fadeOut: {
            opacity: "hide"
        },
        fadeToggle: {
            opacity: "toggle"
        }
    }, function(name, props) {
        jQuery.fn[name] = function(speed, easing, callback) {
            return this.animate(props, speed, easing, callback);
        };
    });
    jQuery.timers = [];
    jQuery.fx.tick = function() {
        var timer, i = 0, timers = jQuery.timers;
        fxNow = jQuery.now();
        for (;i < timers.length; i++) {
            timer = timers[i];
            if (!timer() && timers[i] === timer) {
                timers.splice(i--, 1);
            }
        }
        if (!timers.length) {
            jQuery.fx.stop();
        }
        fxNow = undefined;
    };
    jQuery.fx.timer = function(timer) {
        jQuery.timers.push(timer);
        if (timer()) {
            jQuery.fx.start();
        } else {
            jQuery.timers.pop();
        }
    };
    jQuery.fx.interval = 13;
    jQuery.fx.start = function() {
        if (!timerId) {
            timerId = setInterval(jQuery.fx.tick, jQuery.fx.interval);
        }
    };
    jQuery.fx.stop = function() {
        clearInterval(timerId);
        timerId = null;
    };
    jQuery.fx.speeds = {
        slow: 600,
        fast: 200,
        _default: 400
    };
    jQuery.fn.delay = function(time, type) {
        time = jQuery.fx ? jQuery.fx.speeds[time] || time : time;
        type = type || "fx";
        return this.queue(type, function(next, hooks) {
            var timeout = setTimeout(next, time);
            hooks.stop = function() {
                clearTimeout(timeout);
            };
        });
    };
    (function() {
        var input = document.createElement("input"), select = document.createElement("select"), opt = select.appendChild(document.createElement("option"));
        input.type = "checkbox";
        support.checkOn = input.value !== "";
        support.optSelected = opt.selected;
        select.disabled = true;
        support.optDisabled = !opt.disabled;
        input = document.createElement("input");
        input.value = "t";
        input.type = "radio";
        support.radioValue = input.value === "t";
    })();
    var nodeHook, boolHook, attrHandle = jQuery.expr.attrHandle;
    jQuery.fn.extend({
        attr: function(name, value) {
            return access(this, jQuery.attr, name, value, arguments.length > 1);
        },
        removeAttr: function(name) {
            return this.each(function() {
                jQuery.removeAttr(this, name);
            });
        }
    });
    jQuery.extend({
        attr: function(elem, name, value) {
            var hooks, ret, nType = elem.nodeType;
            if (!elem || nType === 3 || nType === 8 || nType === 2) {
                return;
            }
            if (typeof elem.getAttribute === strundefined) {
                return jQuery.prop(elem, name, value);
            }
            if (nType !== 1 || !jQuery.isXMLDoc(elem)) {
                name = name.toLowerCase();
                hooks = jQuery.attrHooks[name] || (jQuery.expr.match.bool.test(name) ? boolHook : nodeHook);
            }
            if (value !== undefined) {
                if (value === null) {
                    jQuery.removeAttr(elem, name);
                } else if (hooks && "set" in hooks && (ret = hooks.set(elem, value, name)) !== undefined) {
                    return ret;
                } else {
                    elem.setAttribute(name, value + "");
                    return value;
                }
            } else if (hooks && "get" in hooks && (ret = hooks.get(elem, name)) !== null) {
                return ret;
            } else {
                ret = jQuery.find.attr(elem, name);
                return ret == null ? undefined : ret;
            }
        },
        removeAttr: function(elem, value) {
            var name, propName, i = 0, attrNames = value && value.match(rnotwhite);
            if (attrNames && elem.nodeType === 1) {
                while (name = attrNames[i++]) {
                    propName = jQuery.propFix[name] || name;
                    if (jQuery.expr.match.bool.test(name)) {
                        elem[propName] = false;
                    }
                    elem.removeAttribute(name);
                }
            }
        },
        attrHooks: {
            type: {
                set: function(elem, value) {
                    if (!support.radioValue && value === "radio" && jQuery.nodeName(elem, "input")) {
                        var val = elem.value;
                        elem.setAttribute("type", value);
                        if (val) {
                            elem.value = val;
                        }
                        return value;
                    }
                }
            }
        }
    });
    boolHook = {
        set: function(elem, value, name) {
            if (value === false) {
                jQuery.removeAttr(elem, name);
            } else {
                elem.setAttribute(name, name);
            }
            return name;
        }
    };
    jQuery.each(jQuery.expr.match.bool.source.match(/\w+/g), function(i, name) {
        var getter = attrHandle[name] || jQuery.find.attr;
        attrHandle[name] = function(elem, name, isXML) {
            var ret, handle;
            if (!isXML) {
                handle = attrHandle[name];
                attrHandle[name] = ret;
                ret = getter(elem, name, isXML) != null ? name.toLowerCase() : null;
                attrHandle[name] = handle;
            }
            return ret;
        };
    });
    var rfocusable = /^(?:input|select|textarea|button)$/i;
    jQuery.fn.extend({
        prop: function(name, value) {
            return access(this, jQuery.prop, name, value, arguments.length > 1);
        },
        removeProp: function(name) {
            return this.each(function() {
                delete this[jQuery.propFix[name] || name];
            });
        }
    });
    jQuery.extend({
        propFix: {
            "for": "htmlFor",
            "class": "className"
        },
        prop: function(elem, name, value) {
            var ret, hooks, notxml, nType = elem.nodeType;
            if (!elem || nType === 3 || nType === 8 || nType === 2) {
                return;
            }
            notxml = nType !== 1 || !jQuery.isXMLDoc(elem);
            if (notxml) {
                name = jQuery.propFix[name] || name;
                hooks = jQuery.propHooks[name];
            }
            if (value !== undefined) {
                return hooks && "set" in hooks && (ret = hooks.set(elem, value, name)) !== undefined ? ret : elem[name] = value;
            } else {
                return hooks && "get" in hooks && (ret = hooks.get(elem, name)) !== null ? ret : elem[name];
            }
        },
        propHooks: {
            tabIndex: {
                get: function(elem) {
                    return elem.hasAttribute("tabindex") || rfocusable.test(elem.nodeName) || elem.href ? elem.tabIndex : -1;
                }
            }
        }
    });
    if (!support.optSelected) {
        jQuery.propHooks.selected = {
            get: function(elem) {
                var parent = elem.parentNode;
                if (parent && parent.parentNode) {
                    parent.parentNode.selectedIndex;
                }
                return null;
            }
        };
    }
    jQuery.each([ "tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable" ], function() {
        jQuery.propFix[this.toLowerCase()] = this;
    });
    var rclass = /[\t\r\n\f]/g;
    jQuery.fn.extend({
        addClass: function(value) {
            var classes, elem, cur, clazz, j, finalValue, proceed = typeof value === "string" && value, i = 0, len = this.length;
            if (jQuery.isFunction(value)) {
                return this.each(function(j) {
                    jQuery(this).addClass(value.call(this, j, this.className));
                });
            }
            if (proceed) {
                classes = (value || "").match(rnotwhite) || [];
                for (;i < len; i++) {
                    elem = this[i];
                    cur = elem.nodeType === 1 && (elem.className ? (" " + elem.className + " ").replace(rclass, " ") : " ");
                    if (cur) {
                        j = 0;
                        while (clazz = classes[j++]) {
                            if (cur.indexOf(" " + clazz + " ") < 0) {
                                cur += clazz + " ";
                            }
                        }
                        finalValue = jQuery.trim(cur);
                        if (elem.className !== finalValue) {
                            elem.className = finalValue;
                        }
                    }
                }
            }
            return this;
        },
        removeClass: function(value) {
            var classes, elem, cur, clazz, j, finalValue, proceed = arguments.length === 0 || typeof value === "string" && value, i = 0, len = this.length;
            if (jQuery.isFunction(value)) {
                return this.each(function(j) {
                    jQuery(this).removeClass(value.call(this, j, this.className));
                });
            }
            if (proceed) {
                classes = (value || "").match(rnotwhite) || [];
                for (;i < len; i++) {
                    elem = this[i];
                    cur = elem.nodeType === 1 && (elem.className ? (" " + elem.className + " ").replace(rclass, " ") : "");
                    if (cur) {
                        j = 0;
                        while (clazz = classes[j++]) {
                            while (cur.indexOf(" " + clazz + " ") >= 0) {
                                cur = cur.replace(" " + clazz + " ", " ");
                            }
                        }
                        finalValue = value ? jQuery.trim(cur) : "";
                        if (elem.className !== finalValue) {
                            elem.className = finalValue;
                        }
                    }
                }
            }
            return this;
        },
        toggleClass: function(value, stateVal) {
            var type = typeof value;
            if (typeof stateVal === "boolean" && type === "string") {
                return stateVal ? this.addClass(value) : this.removeClass(value);
            }
            if (jQuery.isFunction(value)) {
                return this.each(function(i) {
                    jQuery(this).toggleClass(value.call(this, i, this.className, stateVal), stateVal);
                });
            }
            return this.each(function() {
                if (type === "string") {
                    var className, i = 0, self = jQuery(this), classNames = value.match(rnotwhite) || [];
                    while (className = classNames[i++]) {
                        if (self.hasClass(className)) {
                            self.removeClass(className);
                        } else {
                            self.addClass(className);
                        }
                    }
                } else if (type === strundefined || type === "boolean") {
                    if (this.className) {
                        data_priv.set(this, "__className__", this.className);
                    }
                    this.className = this.className || value === false ? "" : data_priv.get(this, "__className__") || "";
                }
            });
        },
        hasClass: function(selector) {
            var className = " " + selector + " ", i = 0, l = this.length;
            for (;i < l; i++) {
                if (this[i].nodeType === 1 && (" " + this[i].className + " ").replace(rclass, " ").indexOf(className) >= 0) {
                    return true;
                }
            }
            return false;
        }
    });
    var rreturn = /\r/g;
    jQuery.fn.extend({
        val: function(value) {
            var hooks, ret, isFunction, elem = this[0];
            if (!arguments.length) {
                if (elem) {
                    hooks = jQuery.valHooks[elem.type] || jQuery.valHooks[elem.nodeName.toLowerCase()];
                    if (hooks && "get" in hooks && (ret = hooks.get(elem, "value")) !== undefined) {
                        return ret;
                    }
                    ret = elem.value;
                    return typeof ret === "string" ? ret.replace(rreturn, "") : ret == null ? "" : ret;
                }
                return;
            }
            isFunction = jQuery.isFunction(value);
            return this.each(function(i) {
                var val;
                if (this.nodeType !== 1) {
                    return;
                }
                if (isFunction) {
                    val = value.call(this, i, jQuery(this).val());
                } else {
                    val = value;
                }
                if (val == null) {
                    val = "";
                } else if (typeof val === "number") {
                    val += "";
                } else if (jQuery.isArray(val)) {
                    val = jQuery.map(val, function(value) {
                        return value == null ? "" : value + "";
                    });
                }
                hooks = jQuery.valHooks[this.type] || jQuery.valHooks[this.nodeName.toLowerCase()];
                if (!hooks || !("set" in hooks) || hooks.set(this, val, "value") === undefined) {
                    this.value = val;
                }
            });
        }
    });
    jQuery.extend({
        valHooks: {
            option: {
                get: function(elem) {
                    var val = jQuery.find.attr(elem, "value");
                    return val != null ? val : jQuery.trim(jQuery.text(elem));
                }
            },
            select: {
                get: function(elem) {
                    var value, option, options = elem.options, index = elem.selectedIndex, one = elem.type === "select-one" || index < 0, values = one ? null : [], max = one ? index + 1 : options.length, i = index < 0 ? max : one ? index : 0;
                    for (;i < max; i++) {
                        option = options[i];
                        if ((option.selected || i === index) && (support.optDisabled ? !option.disabled : option.getAttribute("disabled") === null) && (!option.parentNode.disabled || !jQuery.nodeName(option.parentNode, "optgroup"))) {
                            value = jQuery(option).val();
                            if (one) {
                                return value;
                            }
                            values.push(value);
                        }
                    }
                    return values;
                },
                set: function(elem, value) {
                    var optionSet, option, options = elem.options, values = jQuery.makeArray(value), i = options.length;
                    while (i--) {
                        option = options[i];
                        if (option.selected = jQuery.inArray(option.value, values) >= 0) {
                            optionSet = true;
                        }
                    }
                    if (!optionSet) {
                        elem.selectedIndex = -1;
                    }
                    return values;
                }
            }
        }
    });
    jQuery.each([ "radio", "checkbox" ], function() {
        jQuery.valHooks[this] = {
            set: function(elem, value) {
                if (jQuery.isArray(value)) {
                    return elem.checked = jQuery.inArray(jQuery(elem).val(), value) >= 0;
                }
            }
        };
        if (!support.checkOn) {
            jQuery.valHooks[this].get = function(elem) {
                return elem.getAttribute("value") === null ? "on" : elem.value;
            };
        }
    });
    jQuery.each(("blur focus focusin focusout load resize scroll unload click dblclick " + "mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave " + "change select submit keydown keypress keyup error contextmenu").split(" "), function(i, name) {
        jQuery.fn[name] = function(data, fn) {
            return arguments.length > 0 ? this.on(name, null, data, fn) : this.trigger(name);
        };
    });
    jQuery.fn.extend({
        hover: function(fnOver, fnOut) {
            return this.mouseenter(fnOver).mouseleave(fnOut || fnOver);
        },
        bind: function(types, data, fn) {
            return this.on(types, null, data, fn);
        },
        unbind: function(types, fn) {
            return this.off(types, null, fn);
        },
        delegate: function(selector, types, data, fn) {
            return this.on(types, selector, data, fn);
        },
        undelegate: function(selector, types, fn) {
            return arguments.length === 1 ? this.off(selector, "**") : this.off(types, selector || "**", fn);
        }
    });
    var nonce = jQuery.now();
    var rquery = /\?/;
    jQuery.parseJSON = function(data) {
        return JSON.parse(data + "");
    };
    jQuery.parseXML = function(data) {
        var xml, tmp;
        if (!data || typeof data !== "string") {
            return null;
        }
        try {
            tmp = new DOMParser();
            xml = tmp.parseFromString(data, "text/xml");
        } catch (e) {
            xml = undefined;
        }
        if (!xml || xml.getElementsByTagName("parsererror").length) {
            jQuery.error("Invalid XML: " + data);
        }
        return xml;
    };
    var rhash = /#.*$/, rts = /([?&])_=[^&]*/, rheaders = /^(.*?):[ \t]*([^\r\n]*)$/gm, rlocalProtocol = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/, rnoContent = /^(?:GET|HEAD)$/, rprotocol = /^\/\//, rurl = /^([\w.+-]+:)(?:\/\/(?:[^\/?#]*@|)([^\/?#:]*)(?::(\d+)|)|)/, prefilters = {}, transports = {}, allTypes = "*/".concat("*"), ajaxLocation = window.location.href, ajaxLocParts = rurl.exec(ajaxLocation.toLowerCase()) || [];
    function addToPrefiltersOrTransports(structure) {
        return function(dataTypeExpression, func) {
            if (typeof dataTypeExpression !== "string") {
                func = dataTypeExpression;
                dataTypeExpression = "*";
            }
            var dataType, i = 0, dataTypes = dataTypeExpression.toLowerCase().match(rnotwhite) || [];
            if (jQuery.isFunction(func)) {
                while (dataType = dataTypes[i++]) {
                    if (dataType[0] === "+") {
                        dataType = dataType.slice(1) || "*";
                        (structure[dataType] = structure[dataType] || []).unshift(func);
                    } else {
                        (structure[dataType] = structure[dataType] || []).push(func);
                    }
                }
            }
        };
    }
    function inspectPrefiltersOrTransports(structure, options, originalOptions, jqXHR) {
        var inspected = {}, seekingTransport = structure === transports;
        function inspect(dataType) {
            var selected;
            inspected[dataType] = true;
            jQuery.each(structure[dataType] || [], function(_, prefilterOrFactory) {
                var dataTypeOrTransport = prefilterOrFactory(options, originalOptions, jqXHR);
                if (typeof dataTypeOrTransport === "string" && !seekingTransport && !inspected[dataTypeOrTransport]) {
                    options.dataTypes.unshift(dataTypeOrTransport);
                    inspect(dataTypeOrTransport);
                    return false;
                } else if (seekingTransport) {
                    return !(selected = dataTypeOrTransport);
                }
            });
            return selected;
        }
        return inspect(options.dataTypes[0]) || !inspected["*"] && inspect("*");
    }
    function ajaxExtend(target, src) {
        var key, deep, flatOptions = jQuery.ajaxSettings.flatOptions || {};
        for (key in src) {
            if (src[key] !== undefined) {
                (flatOptions[key] ? target : deep || (deep = {}))[key] = src[key];
            }
        }
        if (deep) {
            jQuery.extend(true, target, deep);
        }
        return target;
    }
    function ajaxHandleResponses(s, jqXHR, responses) {
        var ct, type, finalDataType, firstDataType, contents = s.contents, dataTypes = s.dataTypes;
        while (dataTypes[0] === "*") {
            dataTypes.shift();
            if (ct === undefined) {
                ct = s.mimeType || jqXHR.getResponseHeader("Content-Type");
            }
        }
        if (ct) {
            for (type in contents) {
                if (contents[type] && contents[type].test(ct)) {
                    dataTypes.unshift(type);
                    break;
                }
            }
        }
        if (dataTypes[0] in responses) {
            finalDataType = dataTypes[0];
        } else {
            for (type in responses) {
                if (!dataTypes[0] || s.converters[type + " " + dataTypes[0]]) {
                    finalDataType = type;
                    break;
                }
                if (!firstDataType) {
                    firstDataType = type;
                }
            }
            finalDataType = finalDataType || firstDataType;
        }
        if (finalDataType) {
            if (finalDataType !== dataTypes[0]) {
                dataTypes.unshift(finalDataType);
            }
            return responses[finalDataType];
        }
    }
    function ajaxConvert(s, response, jqXHR, isSuccess) {
        var conv2, current, conv, tmp, prev, converters = {}, dataTypes = s.dataTypes.slice();
        if (dataTypes[1]) {
            for (conv in s.converters) {
                converters[conv.toLowerCase()] = s.converters[conv];
            }
        }
        current = dataTypes.shift();
        while (current) {
            if (s.responseFields[current]) {
                jqXHR[s.responseFields[current]] = response;
            }
            if (!prev && isSuccess && s.dataFilter) {
                response = s.dataFilter(response, s.dataType);
            }
            prev = current;
            current = dataTypes.shift();
            if (current) {
                if (current === "*") {
                    current = prev;
                } else if (prev !== "*" && prev !== current) {
                    conv = converters[prev + " " + current] || converters["* " + current];
                    if (!conv) {
                        for (conv2 in converters) {
                            tmp = conv2.split(" ");
                            if (tmp[1] === current) {
                                conv = converters[prev + " " + tmp[0]] || converters["* " + tmp[0]];
                                if (conv) {
                                    if (conv === true) {
                                        conv = converters[conv2];
                                    } else if (converters[conv2] !== true) {
                                        current = tmp[0];
                                        dataTypes.unshift(tmp[1]);
                                    }
                                    break;
                                }
                            }
                        }
                    }
                    if (conv !== true) {
                        if (conv && s["throws"]) {
                            response = conv(response);
                        } else {
                            try {
                                response = conv(response);
                            } catch (e) {
                                return {
                                    state: "parsererror",
                                    error: conv ? e : "No conversion from " + prev + " to " + current
                                };
                            }
                        }
                    }
                }
            }
        }
        return {
            state: "success",
            data: response
        };
    }
    jQuery.extend({
        active: 0,
        lastModified: {},
        etag: {},
        ajaxSettings: {
            url: ajaxLocation,
            type: "GET",
            isLocal: rlocalProtocol.test(ajaxLocParts[1]),
            global: true,
            processData: true,
            async: true,
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            accepts: {
                "*": allTypes,
                text: "text/plain",
                html: "text/html",
                xml: "application/xml, text/xml",
                json: "application/json, text/javascript"
            },
            contents: {
                xml: /xml/,
                html: /html/,
                json: /json/
            },
            responseFields: {
                xml: "responseXML",
                text: "responseText",
                json: "responseJSON"
            },
            converters: {
                "* text": String,
                "text html": true,
                "text json": jQuery.parseJSON,
                "text xml": jQuery.parseXML
            },
            flatOptions: {
                url: true,
                context: true
            }
        },
        ajaxSetup: function(target, settings) {
            return settings ? ajaxExtend(ajaxExtend(target, jQuery.ajaxSettings), settings) : ajaxExtend(jQuery.ajaxSettings, target);
        },
        ajaxPrefilter: addToPrefiltersOrTransports(prefilters),
        ajaxTransport: addToPrefiltersOrTransports(transports),
        ajax: function(url, options) {
            if (typeof url === "object") {
                options = url;
                url = undefined;
            }
            options = options || {};
            var transport, cacheURL, responseHeadersString, responseHeaders, timeoutTimer, parts, fireGlobals, i, s = jQuery.ajaxSetup({}, options), callbackContext = s.context || s, globalEventContext = s.context && (callbackContext.nodeType || callbackContext.jquery) ? jQuery(callbackContext) : jQuery.event, deferred = jQuery.Deferred(), completeDeferred = jQuery.Callbacks("once memory"), statusCode = s.statusCode || {}, requestHeaders = {}, requestHeadersNames = {}, state = 0, strAbort = "canceled", jqXHR = {
                readyState: 0,
                getResponseHeader: function(key) {
                    var match;
                    if (state === 2) {
                        if (!responseHeaders) {
                            responseHeaders = {};
                            while (match = rheaders.exec(responseHeadersString)) {
                                responseHeaders[match[1].toLowerCase()] = match[2];
                            }
                        }
                        match = responseHeaders[key.toLowerCase()];
                    }
                    return match == null ? null : match;
                },
                getAllResponseHeaders: function() {
                    return state === 2 ? responseHeadersString : null;
                },
                setRequestHeader: function(name, value) {
                    var lname = name.toLowerCase();
                    if (!state) {
                        name = requestHeadersNames[lname] = requestHeadersNames[lname] || name;
                        requestHeaders[name] = value;
                    }
                    return this;
                },
                overrideMimeType: function(type) {
                    if (!state) {
                        s.mimeType = type;
                    }
                    return this;
                },
                statusCode: function(map) {
                    var code;
                    if (map) {
                        if (state < 2) {
                            for (code in map) {
                                statusCode[code] = [ statusCode[code], map[code] ];
                            }
                        } else {
                            jqXHR.always(map[jqXHR.status]);
                        }
                    }
                    return this;
                },
                abort: function(statusText) {
                    var finalText = statusText || strAbort;
                    if (transport) {
                        transport.abort(finalText);
                    }
                    done(0, finalText);
                    return this;
                }
            };
            deferred.promise(jqXHR).complete = completeDeferred.add;
            jqXHR.success = jqXHR.done;
            jqXHR.error = jqXHR.fail;
            s.url = ((url || s.url || ajaxLocation) + "").replace(rhash, "").replace(rprotocol, ajaxLocParts[1] + "//");
            s.type = options.method || options.type || s.method || s.type;
            s.dataTypes = jQuery.trim(s.dataType || "*").toLowerCase().match(rnotwhite) || [ "" ];
            if (s.crossDomain == null) {
                parts = rurl.exec(s.url.toLowerCase());
                s.crossDomain = !!(parts && (parts[1] !== ajaxLocParts[1] || parts[2] !== ajaxLocParts[2] || (parts[3] || (parts[1] === "http:" ? "80" : "443")) !== (ajaxLocParts[3] || (ajaxLocParts[1] === "http:" ? "80" : "443"))));
            }
            if (s.data && s.processData && typeof s.data !== "string") {
                s.data = jQuery.param(s.data, s.traditional);
            }
            inspectPrefiltersOrTransports(prefilters, s, options, jqXHR);
            if (state === 2) {
                return jqXHR;
            }
            fireGlobals = jQuery.event && s.global;
            if (fireGlobals && jQuery.active++ === 0) {
                jQuery.event.trigger("ajaxStart");
            }
            s.type = s.type.toUpperCase();
            s.hasContent = !rnoContent.test(s.type);
            cacheURL = s.url;
            if (!s.hasContent) {
                if (s.data) {
                    cacheURL = s.url += (rquery.test(cacheURL) ? "&" : "?") + s.data;
                    delete s.data;
                }
                if (s.cache === false) {
                    s.url = rts.test(cacheURL) ? cacheURL.replace(rts, "$1_=" + nonce++) : cacheURL + (rquery.test(cacheURL) ? "&" : "?") + "_=" + nonce++;
                }
            }
            if (s.ifModified) {
                if (jQuery.lastModified[cacheURL]) {
                    jqXHR.setRequestHeader("If-Modified-Since", jQuery.lastModified[cacheURL]);
                }
                if (jQuery.etag[cacheURL]) {
                    jqXHR.setRequestHeader("If-None-Match", jQuery.etag[cacheURL]);
                }
            }
            if (s.data && s.hasContent && s.contentType !== false || options.contentType) {
                jqXHR.setRequestHeader("Content-Type", s.contentType);
            }
            jqXHR.setRequestHeader("Accept", s.dataTypes[0] && s.accepts[s.dataTypes[0]] ? s.accepts[s.dataTypes[0]] + (s.dataTypes[0] !== "*" ? ", " + allTypes + "; q=0.01" : "") : s.accepts["*"]);
            for (i in s.headers) {
                jqXHR.setRequestHeader(i, s.headers[i]);
            }
            if (s.beforeSend && (s.beforeSend.call(callbackContext, jqXHR, s) === false || state === 2)) {
                return jqXHR.abort();
            }
            strAbort = "abort";
            for (i in {
                success: 1,
                error: 1,
                complete: 1
            }) {
                jqXHR[i](s[i]);
            }
            transport = inspectPrefiltersOrTransports(transports, s, options, jqXHR);
            if (!transport) {
                done(-1, "No Transport");
            } else {
                jqXHR.readyState = 1;
                if (fireGlobals) {
                    globalEventContext.trigger("ajaxSend", [ jqXHR, s ]);
                }
                if (s.async && s.timeout > 0) {
                    timeoutTimer = setTimeout(function() {
                        jqXHR.abort("timeout");
                    }, s.timeout);
                }
                try {
                    state = 1;
                    transport.send(requestHeaders, done);
                } catch (e) {
                    if (state < 2) {
                        done(-1, e);
                    } else {
                        throw e;
                    }
                }
            }
            function done(status, nativeStatusText, responses, headers) {
                var isSuccess, success, error, response, modified, statusText = nativeStatusText;
                if (state === 2) {
                    return;
                }
                state = 2;
                if (timeoutTimer) {
                    clearTimeout(timeoutTimer);
                }
                transport = undefined;
                responseHeadersString = headers || "";
                jqXHR.readyState = status > 0 ? 4 : 0;
                isSuccess = status >= 200 && status < 300 || status === 304;
                if (responses) {
                    response = ajaxHandleResponses(s, jqXHR, responses);
                }
                response = ajaxConvert(s, response, jqXHR, isSuccess);
                if (isSuccess) {
                    if (s.ifModified) {
                        modified = jqXHR.getResponseHeader("Last-Modified");
                        if (modified) {
                            jQuery.lastModified[cacheURL] = modified;
                        }
                        modified = jqXHR.getResponseHeader("etag");
                        if (modified) {
                            jQuery.etag[cacheURL] = modified;
                        }
                    }
                    if (status === 204 || s.type === "HEAD") {
                        statusText = "nocontent";
                    } else if (status === 304) {
                        statusText = "notmodified";
                    } else {
                        statusText = response.state;
                        success = response.data;
                        error = response.error;
                        isSuccess = !error;
                    }
                } else {
                    error = statusText;
                    if (status || !statusText) {
                        statusText = "error";
                        if (status < 0) {
                            status = 0;
                        }
                    }
                }
                jqXHR.status = status;
                jqXHR.statusText = (nativeStatusText || statusText) + "";
                if (isSuccess) {
                    deferred.resolveWith(callbackContext, [ success, statusText, jqXHR ]);
                } else {
                    deferred.rejectWith(callbackContext, [ jqXHR, statusText, error ]);
                }
                jqXHR.statusCode(statusCode);
                statusCode = undefined;
                if (fireGlobals) {
                    globalEventContext.trigger(isSuccess ? "ajaxSuccess" : "ajaxError", [ jqXHR, s, isSuccess ? success : error ]);
                }
                completeDeferred.fireWith(callbackContext, [ jqXHR, statusText ]);
                if (fireGlobals) {
                    globalEventContext.trigger("ajaxComplete", [ jqXHR, s ]);
                    if (!--jQuery.active) {
                        jQuery.event.trigger("ajaxStop");
                    }
                }
            }
            return jqXHR;
        },
        getJSON: function(url, data, callback) {
            return jQuery.get(url, data, callback, "json");
        },
        getScript: function(url, callback) {
            return jQuery.get(url, undefined, callback, "script");
        }
    });
    jQuery.each([ "get", "post" ], function(i, method) {
        jQuery[method] = function(url, data, callback, type) {
            if (jQuery.isFunction(data)) {
                type = type || callback;
                callback = data;
                data = undefined;
            }
            return jQuery.ajax({
                url: url,
                type: method,
                dataType: type,
                data: data,
                success: callback
            });
        };
    });
    jQuery._evalUrl = function(url) {
        return jQuery.ajax({
            url: url,
            type: "GET",
            dataType: "script",
            async: false,
            global: false,
            "throws": true
        });
    };
    jQuery.fn.extend({
        wrapAll: function(html) {
            var wrap;
            if (jQuery.isFunction(html)) {
                return this.each(function(i) {
                    jQuery(this).wrapAll(html.call(this, i));
                });
            }
            if (this[0]) {
                wrap = jQuery(html, this[0].ownerDocument).eq(0).clone(true);
                if (this[0].parentNode) {
                    wrap.insertBefore(this[0]);
                }
                wrap.map(function() {
                    var elem = this;
                    while (elem.firstElementChild) {
                        elem = elem.firstElementChild;
                    }
                    return elem;
                }).append(this);
            }
            return this;
        },
        wrapInner: function(html) {
            if (jQuery.isFunction(html)) {
                return this.each(function(i) {
                    jQuery(this).wrapInner(html.call(this, i));
                });
            }
            return this.each(function() {
                var self = jQuery(this), contents = self.contents();
                if (contents.length) {
                    contents.wrapAll(html);
                } else {
                    self.append(html);
                }
            });
        },
        wrap: function(html) {
            var isFunction = jQuery.isFunction(html);
            return this.each(function(i) {
                jQuery(this).wrapAll(isFunction ? html.call(this, i) : html);
            });
        },
        unwrap: function() {
            return this.parent().each(function() {
                if (!jQuery.nodeName(this, "body")) {
                    jQuery(this).replaceWith(this.childNodes);
                }
            }).end();
        }
    });
    jQuery.expr.filters.hidden = function(elem) {
        return elem.offsetWidth <= 0 && elem.offsetHeight <= 0;
    };
    jQuery.expr.filters.visible = function(elem) {
        return !jQuery.expr.filters.hidden(elem);
    };
    var r20 = /%20/g, rbracket = /\[\]$/, rCRLF = /\r?\n/g, rsubmitterTypes = /^(?:submit|button|image|reset|file)$/i, rsubmittable = /^(?:input|select|textarea|keygen)/i;
    function buildParams(prefix, obj, traditional, add) {
        var name;
        if (jQuery.isArray(obj)) {
            jQuery.each(obj, function(i, v) {
                if (traditional || rbracket.test(prefix)) {
                    add(prefix, v);
                } else {
                    buildParams(prefix + "[" + (typeof v === "object" ? i : "") + "]", v, traditional, add);
                }
            });
        } else if (!traditional && jQuery.type(obj) === "object") {
            for (name in obj) {
                buildParams(prefix + "[" + name + "]", obj[name], traditional, add);
            }
        } else {
            add(prefix, obj);
        }
    }
    jQuery.param = function(a, traditional) {
        var prefix, s = [], add = function(key, value) {
            value = jQuery.isFunction(value) ? value() : value == null ? "" : value;
            s[s.length] = encodeURIComponent(key) + "=" + encodeURIComponent(value);
        };
        if (traditional === undefined) {
            traditional = jQuery.ajaxSettings && jQuery.ajaxSettings.traditional;
        }
        if (jQuery.isArray(a) || a.jquery && !jQuery.isPlainObject(a)) {
            jQuery.each(a, function() {
                add(this.name, this.value);
            });
        } else {
            for (prefix in a) {
                buildParams(prefix, a[prefix], traditional, add);
            }
        }
        return s.join("&").replace(r20, "+");
    };
    jQuery.fn.extend({
        serialize: function() {
            return jQuery.param(this.serializeArray());
        },
        serializeArray: function() {
            return this.map(function() {
                var elements = jQuery.prop(this, "elements");
                return elements ? jQuery.makeArray(elements) : this;
            }).filter(function() {
                var type = this.type;
                return this.name && !jQuery(this).is(":disabled") && rsubmittable.test(this.nodeName) && !rsubmitterTypes.test(type) && (this.checked || !rcheckableType.test(type));
            }).map(function(i, elem) {
                var val = jQuery(this).val();
                return val == null ? null : jQuery.isArray(val) ? jQuery.map(val, function(val) {
                    return {
                        name: elem.name,
                        value: val.replace(rCRLF, "\r\n")
                    };
                }) : {
                    name: elem.name,
                    value: val.replace(rCRLF, "\r\n")
                };
            }).get();
        }
    });
    jQuery.ajaxSettings.xhr = function() {
        try {
            return new XMLHttpRequest();
        } catch (e) {}
    };
    var xhrId = 0, xhrCallbacks = {}, xhrSuccessStatus = {
        0: 200,
        1223: 204
    }, xhrSupported = jQuery.ajaxSettings.xhr();
    if (window.attachEvent) {
        window.attachEvent("onunload", function() {
            for (var key in xhrCallbacks) {
                xhrCallbacks[key]();
            }
        });
    }
    support.cors = !!xhrSupported && "withCredentials" in xhrSupported;
    support.ajax = xhrSupported = !!xhrSupported;
    jQuery.ajaxTransport(function(options) {
        var callback;
        if (support.cors || xhrSupported && !options.crossDomain) {
            return {
                send: function(headers, complete) {
                    var i, xhr = options.xhr(), id = ++xhrId;
                    xhr.open(options.type, options.url, options.async, options.username, options.password);
                    if (options.xhrFields) {
                        for (i in options.xhrFields) {
                            xhr[i] = options.xhrFields[i];
                        }
                    }
                    if (options.mimeType && xhr.overrideMimeType) {
                        xhr.overrideMimeType(options.mimeType);
                    }
                    if (!options.crossDomain && !headers["X-Requested-With"]) {
                        headers["X-Requested-With"] = "XMLHttpRequest";
                    }
                    for (i in headers) {
                        xhr.setRequestHeader(i, headers[i]);
                    }
                    callback = function(type) {
                        return function() {
                            if (callback) {
                                delete xhrCallbacks[id];
                                callback = xhr.onload = xhr.onerror = null;
                                if (type === "abort") {
                                    xhr.abort();
                                } else if (type === "error") {
                                    complete(xhr.status, xhr.statusText);
                                } else {
                                    complete(xhrSuccessStatus[xhr.status] || xhr.status, xhr.statusText, typeof xhr.responseText === "string" ? {
                                        text: xhr.responseText
                                    } : undefined, xhr.getAllResponseHeaders());
                                }
                            }
                        };
                    };
                    xhr.onload = callback();
                    xhr.onerror = callback("error");
                    callback = xhrCallbacks[id] = callback("abort");
                    try {
                        xhr.send(options.hasContent && options.data || null);
                    } catch (e) {
                        if (callback) {
                            throw e;
                        }
                    }
                },
                abort: function() {
                    if (callback) {
                        callback();
                    }
                }
            };
        }
    });
    jQuery.ajaxSetup({
        accepts: {
            script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"
        },
        contents: {
            script: /(?:java|ecma)script/
        },
        converters: {
            "text script": function(text) {
                jQuery.globalEval(text);
                return text;
            }
        }
    });
    jQuery.ajaxPrefilter("script", function(s) {
        if (s.cache === undefined) {
            s.cache = false;
        }
        if (s.crossDomain) {
            s.type = "GET";
        }
    });
    jQuery.ajaxTransport("script", function(s) {
        if (s.crossDomain) {
            var script, callback;
            return {
                send: function(_, complete) {
                    script = jQuery("<script>").prop({
                        async: true,
                        charset: s.scriptCharset,
                        src: s.url
                    }).on("load error", callback = function(evt) {
                        script.remove();
                        callback = null;
                        if (evt) {
                            complete(evt.type === "error" ? 404 : 200, evt.type);
                        }
                    });
                    document.head.appendChild(script[0]);
                },
                abort: function() {
                    if (callback) {
                        callback();
                    }
                }
            };
        }
    });
    var oldCallbacks = [], rjsonp = /(=)\?(?=&|$)|\?\?/;
    jQuery.ajaxSetup({
        jsonp: "callback",
        jsonpCallback: function() {
            var callback = oldCallbacks.pop() || jQuery.expando + "_" + nonce++;
            this[callback] = true;
            return callback;
        }
    });
    jQuery.ajaxPrefilter("json jsonp", function(s, originalSettings, jqXHR) {
        var callbackName, overwritten, responseContainer, jsonProp = s.jsonp !== false && (rjsonp.test(s.url) ? "url" : typeof s.data === "string" && !(s.contentType || "").indexOf("application/x-www-form-urlencoded") && rjsonp.test(s.data) && "data");
        if (jsonProp || s.dataTypes[0] === "jsonp") {
            callbackName = s.jsonpCallback = jQuery.isFunction(s.jsonpCallback) ? s.jsonpCallback() : s.jsonpCallback;
            if (jsonProp) {
                s[jsonProp] = s[jsonProp].replace(rjsonp, "$1" + callbackName);
            } else if (s.jsonp !== false) {
                s.url += (rquery.test(s.url) ? "&" : "?") + s.jsonp + "=" + callbackName;
            }
            s.converters["script json"] = function() {
                if (!responseContainer) {
                    jQuery.error(callbackName + " was not called");
                }
                return responseContainer[0];
            };
            s.dataTypes[0] = "json";
            overwritten = window[callbackName];
            window[callbackName] = function() {
                responseContainer = arguments;
            };
            jqXHR.always(function() {
                window[callbackName] = overwritten;
                if (s[callbackName]) {
                    s.jsonpCallback = originalSettings.jsonpCallback;
                    oldCallbacks.push(callbackName);
                }
                if (responseContainer && jQuery.isFunction(overwritten)) {
                    overwritten(responseContainer[0]);
                }
                responseContainer = overwritten = undefined;
            });
            return "script";
        }
    });
    jQuery.parseHTML = function(data, context, keepScripts) {
        if (!data || typeof data !== "string") {
            return null;
        }
        if (typeof context === "boolean") {
            keepScripts = context;
            context = false;
        }
        context = context || document;
        var parsed = rsingleTag.exec(data), scripts = !keepScripts && [];
        if (parsed) {
            return [ context.createElement(parsed[1]) ];
        }
        parsed = jQuery.buildFragment([ data ], context, scripts);
        if (scripts && scripts.length) {
            jQuery(scripts).remove();
        }
        return jQuery.merge([], parsed.childNodes);
    };
    var _load = jQuery.fn.load;
    jQuery.fn.load = function(url, params, callback) {
        if (typeof url !== "string" && _load) {
            return _load.apply(this, arguments);
        }
        var selector, type, response, self = this, off = url.indexOf(" ");
        if (off >= 0) {
            selector = jQuery.trim(url.slice(off));
            url = url.slice(0, off);
        }
        if (jQuery.isFunction(params)) {
            callback = params;
            params = undefined;
        } else if (params && typeof params === "object") {
            type = "POST";
        }
        if (self.length > 0) {
            jQuery.ajax({
                url: url,
                type: type,
                dataType: "html",
                data: params
            }).done(function(responseText) {
                response = arguments;
                self.html(selector ? jQuery("<div>").append(jQuery.parseHTML(responseText)).find(selector) : responseText);
            }).complete(callback && function(jqXHR, status) {
                self.each(callback, response || [ jqXHR.responseText, status, jqXHR ]);
            });
        }
        return this;
    };
    jQuery.each([ "ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend" ], function(i, type) {
        jQuery.fn[type] = function(fn) {
            return this.on(type, fn);
        };
    });
    jQuery.expr.filters.animated = function(elem) {
        return jQuery.grep(jQuery.timers, function(fn) {
            return elem === fn.elem;
        }).length;
    };
    var docElem = window.document.documentElement;
    function getWindow(elem) {
        return jQuery.isWindow(elem) ? elem : elem.nodeType === 9 && elem.defaultView;
    }
    jQuery.offset = {
        setOffset: function(elem, options, i) {
            var curPosition, curLeft, curCSSTop, curTop, curOffset, curCSSLeft, calculatePosition, position = jQuery.css(elem, "position"), curElem = jQuery(elem), props = {};
            if (position === "static") {
                elem.style.position = "relative";
            }
            curOffset = curElem.offset();
            curCSSTop = jQuery.css(elem, "top");
            curCSSLeft = jQuery.css(elem, "left");
            calculatePosition = (position === "absolute" || position === "fixed") && (curCSSTop + curCSSLeft).indexOf("auto") > -1;
            if (calculatePosition) {
                curPosition = curElem.position();
                curTop = curPosition.top;
                curLeft = curPosition.left;
            } else {
                curTop = parseFloat(curCSSTop) || 0;
                curLeft = parseFloat(curCSSLeft) || 0;
            }
            if (jQuery.isFunction(options)) {
                options = options.call(elem, i, curOffset);
            }
            if (options.top != null) {
                props.top = options.top - curOffset.top + curTop;
            }
            if (options.left != null) {
                props.left = options.left - curOffset.left + curLeft;
            }
            if ("using" in options) {
                options.using.call(elem, props);
            } else {
                curElem.css(props);
            }
        }
    };
    jQuery.fn.extend({
        offset: function(options) {
            if (arguments.length) {
                return options === undefined ? this : this.each(function(i) {
                    jQuery.offset.setOffset(this, options, i);
                });
            }
            var docElem, win, elem = this[0], box = {
                top: 0,
                left: 0
            }, doc = elem && elem.ownerDocument;
            if (!doc) {
                return;
            }
            docElem = doc.documentElement;
            if (!jQuery.contains(docElem, elem)) {
                return box;
            }
            if (typeof elem.getBoundingClientRect !== strundefined) {
                box = elem.getBoundingClientRect();
            }
            win = getWindow(doc);
            return {
                top: box.top + win.pageYOffset - docElem.clientTop,
                left: box.left + win.pageXOffset - docElem.clientLeft
            };
        },
        position: function() {
            if (!this[0]) {
                return;
            }
            var offsetParent, offset, elem = this[0], parentOffset = {
                top: 0,
                left: 0
            };
            if (jQuery.css(elem, "position") === "fixed") {
                offset = elem.getBoundingClientRect();
            } else {
                offsetParent = this.offsetParent();
                offset = this.offset();
                if (!jQuery.nodeName(offsetParent[0], "html")) {
                    parentOffset = offsetParent.offset();
                }
                parentOffset.top += jQuery.css(offsetParent[0], "borderTopWidth", true);
                parentOffset.left += jQuery.css(offsetParent[0], "borderLeftWidth", true);
            }
            return {
                top: offset.top - parentOffset.top - jQuery.css(elem, "marginTop", true),
                left: offset.left - parentOffset.left - jQuery.css(elem, "marginLeft", true)
            };
        },
        offsetParent: function() {
            return this.map(function() {
                var offsetParent = this.offsetParent || docElem;
                while (offsetParent && (!jQuery.nodeName(offsetParent, "html") && jQuery.css(offsetParent, "position") === "static")) {
                    offsetParent = offsetParent.offsetParent;
                }
                return offsetParent || docElem;
            });
        }
    });
    jQuery.each({
        scrollLeft: "pageXOffset",
        scrollTop: "pageYOffset"
    }, function(method, prop) {
        var top = "pageYOffset" === prop;
        jQuery.fn[method] = function(val) {
            return access(this, function(elem, method, val) {
                var win = getWindow(elem);
                if (val === undefined) {
                    return win ? win[prop] : elem[method];
                }
                if (win) {
                    win.scrollTo(!top ? val : window.pageXOffset, top ? val : window.pageYOffset);
                } else {
                    elem[method] = val;
                }
            }, method, val, arguments.length, null);
        };
    });
    jQuery.each([ "top", "left" ], function(i, prop) {
        jQuery.cssHooks[prop] = addGetHookIf(support.pixelPosition, function(elem, computed) {
            if (computed) {
                computed = curCSS(elem, prop);
                return rnumnonpx.test(computed) ? jQuery(elem).position()[prop] + "px" : computed;
            }
        });
    });
    jQuery.each({
        Height: "height",
        Width: "width"
    }, function(name, type) {
        jQuery.each({
            padding: "inner" + name,
            content: type,
            "": "outer" + name
        }, function(defaultExtra, funcName) {
            jQuery.fn[funcName] = function(margin, value) {
                var chainable = arguments.length && (defaultExtra || typeof margin !== "boolean"), extra = defaultExtra || (margin === true || value === true ? "margin" : "border");
                return access(this, function(elem, type, value) {
                    var doc;
                    if (jQuery.isWindow(elem)) {
                        return elem.document.documentElement["client" + name];
                    }
                    if (elem.nodeType === 9) {
                        doc = elem.documentElement;
                        return Math.max(elem.body["scroll" + name], doc["scroll" + name], elem.body["offset" + name], doc["offset" + name], doc["client" + name]);
                    }
                    return value === undefined ? jQuery.css(elem, type, extra) : jQuery.style(elem, type, value, extra);
                }, type, chainable ? margin : undefined, chainable, null);
            };
        });
    });
    jQuery.fn.size = function() {
        return this.length;
    };
    jQuery.fn.andSelf = jQuery.fn.addBack;
    if (typeof define === "function" && define.amd) {
        define("jquery", [], function() {
            return jQuery;
        });
    }
    var _jQuery = window.jQuery, _$ = window.$;
    jQuery.noConflict = function(deep) {
        if (window.$ === jQuery) {
            window.$ = _$;
        }
        if (deep && window.jQuery === jQuery) {
            window.jQuery = _jQuery;
        }
        return jQuery;
    };
    if (typeof noGlobal === strundefined) {
        window.jQuery = window.$ = jQuery;
    }
    return jQuery;
});

(function($, window, document, undefined) {
    "use strict";
    var header_helpers = function(class_array) {
        var head = $("head");
        head.prepend($.map(class_array, function(class_name) {
            if (head.has("." + class_name).length === 0) {
                return '<meta class="' + class_name + '" />';
            }
        }));
    };
    header_helpers([ "foundation-mq-small", "foundation-mq-small-only", "foundation-mq-medium", "foundation-mq-medium-only", "foundation-mq-large", "foundation-mq-large-only", "foundation-mq-xlarge", "foundation-mq-xlarge-only", "foundation-mq-xxlarge", "foundation-data-attribute-namespace" ]);
    $(function() {
        if (typeof FastClick !== "undefined") {
            if (typeof document.body !== "undefined") {
                FastClick.attach(document.body);
            }
        }
    });
    var S = function(selector, context) {
        if (typeof selector === "string") {
            if (context) {
                var cont;
                if (context.jquery) {
                    cont = context[0];
                    if (!cont) {
                        return context;
                    }
                } else {
                    cont = context;
                }
                return $(cont.querySelectorAll(selector));
            }
            return $(document.querySelectorAll(selector));
        }
        return $(selector, context);
    };
    var attr_name = function(init) {
        var arr = [];
        if (!init) {
            arr.push("data");
        }
        if (this.namespace.length > 0) {
            arr.push(this.namespace);
        }
        arr.push(this.name);
        return arr.join("-");
    };
    var add_namespace = function(str) {
        var parts = str.split("-"), i = parts.length, arr = [];
        while (i--) {
            if (i !== 0) {
                arr.push(parts[i]);
            } else {
                if (this.namespace.length > 0) {
                    arr.push(this.namespace, parts[i]);
                } else {
                    arr.push(parts[i]);
                }
            }
        }
        return arr.reverse().join("-");
    };
    var bindings = function(method, options) {
        var self = this, bind = function() {
            var $this = S(this), should_bind_events = !$this.data(self.attr_name(true) + "-init");
            $this.data(self.attr_name(true) + "-init", $.extend({}, self.settings, options || method, self.data_options($this)));
            if (should_bind_events) {
                self.events(this);
            }
        };
        if (S(this.scope).is("[" + this.attr_name() + "]")) {
            bind.call(this.scope);
        } else {
            S("[" + this.attr_name() + "]", this.scope).each(bind);
        }
        if (typeof method === "string") {
            return this[method].call(this, options);
        }
    };
    var single_image_loaded = function(image, callback) {
        function loaded() {
            callback(image[0]);
        }
        function bindLoad() {
            this.one("load", loaded);
            if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
                var src = this.attr("src"), param = src.match(/\?/) ? "&" : "?";
                param += "random=" + new Date().getTime();
                this.attr("src", src + param);
            }
        }
        if (!image.attr("src")) {
            loaded();
            return;
        }
        if (image[0].complete || image[0].readyState === 4) {
            loaded();
        } else {
            bindLoad.call(image);
        }
    };
    window.matchMedia || (window.matchMedia = function() {
        "use strict";
        var styleMedia = window.styleMedia || window.media;
        if (!styleMedia) {
            var style = document.createElement("style"), script = document.getElementsByTagName("script")[0], info = null;
            style.type = "text/css";
            style.id = "matchmediajs-test";
            script.parentNode.insertBefore(style, script);
            info = "getComputedStyle" in window && window.getComputedStyle(style, null) || style.currentStyle;
            styleMedia = {
                matchMedium: function(media) {
                    var text = "@media " + media + "{ #matchmediajs-test { width: 1px; } }";
                    if (style.styleSheet) {
                        style.styleSheet.cssText = text;
                    } else {
                        style.textContent = text;
                    }
                    return info.width === "1px";
                }
            };
        }
        return function(media) {
            return {
                matches: styleMedia.matchMedium(media || "all"),
                media: media || "all"
            };
        };
    }());
    (function(jQuery) {
        var animating, lastTime = 0, vendors = [ "webkit", "moz" ], requestAnimationFrame = window.requestAnimationFrame, cancelAnimationFrame = window.cancelAnimationFrame, jqueryFxAvailable = "undefined" !== typeof jQuery.fx;
        for (;lastTime < vendors.length && !requestAnimationFrame; lastTime++) {
            requestAnimationFrame = window[vendors[lastTime] + "RequestAnimationFrame"];
            cancelAnimationFrame = cancelAnimationFrame || window[vendors[lastTime] + "CancelAnimationFrame"] || window[vendors[lastTime] + "CancelRequestAnimationFrame"];
        }
        function raf() {
            if (animating) {
                requestAnimationFrame(raf);
                if (jqueryFxAvailable) {
                    jQuery.fx.tick();
                }
            }
        }
        if (requestAnimationFrame) {
            window.requestAnimationFrame = requestAnimationFrame;
            window.cancelAnimationFrame = cancelAnimationFrame;
            if (jqueryFxAvailable) {
                jQuery.fx.timer = function(timer) {
                    if (timer() && jQuery.timers.push(timer) && !animating) {
                        animating = true;
                        raf();
                    }
                };
                jQuery.fx.stop = function() {
                    animating = false;
                };
            }
        } else {
            window.requestAnimationFrame = function(callback) {
                var currTime = new Date().getTime(), timeToCall = Math.max(0, 16 - (currTime - lastTime)), id = window.setTimeout(function() {
                    callback(currTime + timeToCall);
                }, timeToCall);
                lastTime = currTime + timeToCall;
                return id;
            };
            window.cancelAnimationFrame = function(id) {
                clearTimeout(id);
            };
        }
    })($);
    function removeQuotes(string) {
        if (typeof string === "string" || string instanceof String) {
            string = string.replace(/^['\\/"]+|(;\s?})+|['\\/"]+$/g, "");
        }
        return string;
    }
    function MediaQuery(selector) {
        this.selector = selector;
        this.query = "";
    }
    MediaQuery.prototype.toString = function() {
        return this.query || (this.query = S(this.selector).css("font-family").replace(/^[\/\\'"]+|(;\s?})+|[\/\\'"]+$/g, ""));
    };
    window.Foundation = {
        name: "Foundation",
        version: "5.5.3",
        media_queries: {
            small: new MediaQuery(".foundation-mq-small"),
            "small-only": new MediaQuery(".foundation-mq-small-only"),
            medium: new MediaQuery(".foundation-mq-medium"),
            "medium-only": new MediaQuery(".foundation-mq-medium-only"),
            large: new MediaQuery(".foundation-mq-large"),
            "large-only": new MediaQuery(".foundation-mq-large-only"),
            xlarge: new MediaQuery(".foundation-mq-xlarge"),
            "xlarge-only": new MediaQuery(".foundation-mq-xlarge-only"),
            xxlarge: new MediaQuery(".foundation-mq-xxlarge")
        },
        stylesheet: $("<style></style>").appendTo("head")[0].sheet,
        global: {
            namespace: undefined
        },
        init: function(scope, libraries, method, options, response) {
            var args = [ scope, method, options, response ], responses = [];
            this.rtl = /rtl/i.test(S("html").attr("dir"));
            this.scope = scope || this.scope;
            this.set_namespace();
            if (libraries && typeof libraries === "string" && !/reflow/i.test(libraries)) {
                if (this.libs.hasOwnProperty(libraries)) {
                    responses.push(this.init_lib(libraries, args));
                }
            } else {
                for (var lib in this.libs) {
                    responses.push(this.init_lib(lib, libraries));
                }
            }
            S(window).load(function() {
                S(window).trigger("resize.fndtn.clearing").trigger("resize.fndtn.dropdown").trigger("resize.fndtn.equalizer").trigger("resize.fndtn.interchange").trigger("resize.fndtn.joyride").trigger("resize.fndtn.magellan").trigger("resize.fndtn.topbar").trigger("resize.fndtn.slider");
            });
            return scope;
        },
        init_lib: function(lib, args) {
            if (this.libs.hasOwnProperty(lib)) {
                this.patch(this.libs[lib]);
                if (args && args.hasOwnProperty(lib)) {
                    if (typeof this.libs[lib].settings !== "undefined") {
                        $.extend(true, this.libs[lib].settings, args[lib]);
                    } else if (typeof this.libs[lib].defaults !== "undefined") {
                        $.extend(true, this.libs[lib].defaults, args[lib]);
                    }
                    return this.libs[lib].init.apply(this.libs[lib], [ this.scope, args[lib] ]);
                }
                args = args instanceof Array ? args : new Array(args);
                return this.libs[lib].init.apply(this.libs[lib], args);
            }
            return function() {};
        },
        patch: function(lib) {
            lib.scope = this.scope;
            lib.namespace = this.global.namespace;
            lib.rtl = this.rtl;
            lib["data_options"] = this.utils.data_options;
            lib["attr_name"] = attr_name;
            lib["add_namespace"] = add_namespace;
            lib["bindings"] = bindings;
            lib["S"] = this.utils.S;
        },
        inherit: function(scope, methods) {
            var methods_arr = methods.split(" "), i = methods_arr.length;
            while (i--) {
                if (this.utils.hasOwnProperty(methods_arr[i])) {
                    scope[methods_arr[i]] = this.utils[methods_arr[i]];
                }
            }
        },
        set_namespace: function() {
            var namespace = this.global.namespace === undefined ? $(".foundation-data-attribute-namespace").css("font-family") : this.global.namespace;
            this.global.namespace = namespace === undefined || /false/i.test(namespace) ? "" : namespace;
        },
        libs: {},
        utils: {
            S: S,
            throttle: function(func, delay) {
                var timer = null;
                return function() {
                    var context = this, args = arguments;
                    if (timer == null) {
                        timer = setTimeout(function() {
                            func.apply(context, args);
                            timer = null;
                        }, delay);
                    }
                };
            },
            debounce: function(func, delay, immediate) {
                var timeout, result;
                return function() {
                    var context = this, args = arguments;
                    var later = function() {
                        timeout = null;
                        if (!immediate) {
                            result = func.apply(context, args);
                        }
                    };
                    var callNow = immediate && !timeout;
                    clearTimeout(timeout);
                    timeout = setTimeout(later, delay);
                    if (callNow) {
                        result = func.apply(context, args);
                    }
                    return result;
                };
            },
            data_options: function(el, data_attr_name) {
                data_attr_name = data_attr_name || "options";
                var opts = {}, ii, p, opts_arr, data_options = function(el) {
                    var namespace = Foundation.global.namespace;
                    if (namespace.length > 0) {
                        return el.data(namespace + "-" + data_attr_name);
                    }
                    return el.data(data_attr_name);
                };
                var cached_options = data_options(el);
                if (typeof cached_options === "object") {
                    return cached_options;
                }
                opts_arr = (cached_options || ":").split(";");
                ii = opts_arr.length;
                function isNumber(o) {
                    return !isNaN(o - 0) && o !== null && o !== "" && o !== false && o !== true;
                }
                function trim(str) {
                    if (typeof str === "string") {
                        return $.trim(str);
                    }
                    return str;
                }
                while (ii--) {
                    p = opts_arr[ii].split(":");
                    p = [ p[0], p.slice(1).join(":") ];
                    if (/true/i.test(p[1])) {
                        p[1] = true;
                    }
                    if (/false/i.test(p[1])) {
                        p[1] = false;
                    }
                    if (isNumber(p[1])) {
                        if (p[1].indexOf(".") === -1) {
                            p[1] = parseInt(p[1], 10);
                        } else {
                            p[1] = parseFloat(p[1]);
                        }
                    }
                    if (p.length === 2 && p[0].length > 0) {
                        opts[trim(p[0])] = trim(p[1]);
                    }
                }
                return opts;
            },
            register_media: function(media, media_class) {
                if (Foundation.media_queries[media] === undefined) {
                    $("head").append('<meta class="' + media_class + '"/>');
                    Foundation.media_queries[media] = removeQuotes($("." + media_class).css("font-family"));
                }
            },
            add_custom_rule: function(rule, media) {
                if (media === undefined && Foundation.stylesheet) {
                    Foundation.stylesheet.insertRule(rule, Foundation.stylesheet.cssRules.length);
                } else {
                    var query = Foundation.media_queries[media];
                    if (query !== undefined) {
                        Foundation.stylesheet.insertRule("@media " + Foundation.media_queries[media] + "{ " + rule + " }", Foundation.stylesheet.cssRules.length);
                    }
                }
            },
            image_loaded: function(images, callback) {
                var self = this, unloaded = images.length;
                function pictures_has_height(images) {
                    var pictures_number = images.length;
                    for (var i = pictures_number - 1; i >= 0; i--) {
                        if (images.attr("height") === undefined) {
                            return false;
                        }
                    }
                    return true;
                }
                if (unloaded === 0 || pictures_has_height(images)) {
                    callback(images);
                }
                images.each(function() {
                    single_image_loaded(self.S(this), function() {
                        unloaded -= 1;
                        if (unloaded === 0) {
                            callback(images);
                        }
                    });
                });
            },
            random_str: function() {
                if (!this.fidx) {
                    this.fidx = 0;
                }
                this.prefix = this.prefix || [ this.name || "F", (+new Date()).toString(36) ].join("-");
                return this.prefix + (this.fidx++).toString(36);
            },
            match: function(mq) {
                return window.matchMedia(mq).matches;
            },
            is_small_up: function() {
                return this.match(Foundation.media_queries.small);
            },
            is_medium_up: function() {
                return this.match(Foundation.media_queries.medium);
            },
            is_large_up: function() {
                return this.match(Foundation.media_queries.large);
            },
            is_xlarge_up: function() {
                return this.match(Foundation.media_queries.xlarge);
            },
            is_xxlarge_up: function() {
                return this.match(Foundation.media_queries.xxlarge);
            },
            is_small_only: function() {
                return !this.is_medium_up() && !this.is_large_up() && !this.is_xlarge_up() && !this.is_xxlarge_up();
            },
            is_medium_only: function() {
                return this.is_medium_up() && !this.is_large_up() && !this.is_xlarge_up() && !this.is_xxlarge_up();
            },
            is_large_only: function() {
                return this.is_medium_up() && this.is_large_up() && !this.is_xlarge_up() && !this.is_xxlarge_up();
            },
            is_xlarge_only: function() {
                return this.is_medium_up() && this.is_large_up() && this.is_xlarge_up() && !this.is_xxlarge_up();
            },
            is_xxlarge_only: function() {
                return this.is_medium_up() && this.is_large_up() && this.is_xlarge_up() && this.is_xxlarge_up();
            }
        }
    };
    $.fn.foundation = function() {
        var args = Array.prototype.slice.call(arguments, 0);
        return this.each(function() {
            Foundation.init.apply(Foundation, [ this ].concat(args));
            return this;
        });
    };
})(jQuery, window, window.document);

(function($, window, document, undefined) {
    "use strict";
    Foundation.libs.abide = {
        name: "abide",
        version: "5.5.3",
        settings: {
            live_validate: true,
            validate_on_blur: true,
            focus_on_invalid: true,
            error_labels: true,
            error_class: "error",
            timeout: 1e3,
            patterns: {
                alpha: /^[a-zA-Z]+$/,
                alpha_numeric: /^[a-zA-Z0-9]+$/,
                integer: /^[-+]?\d+$/,
                number: /^[-+]?\d*(?:[\.\,]\d+)?$/,
                card: /^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/,
                cvv: /^([0-9]){3,4}$/,
                email: /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$/,
                url: /^(https?|ftp|file|ssh):\/\/([-;:&=\+\$,\w]+@{1})?([-A-Za-z0-9\.]+)+:?(\d+)?((\/[-\+~%\/\.\w]+)?\??([-\+=&;%@\.\w]+)?#?([\w]+)?)?/,
                domain: /^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,8}$/,
                datetime: /^([0-2][0-9]{3})\-([0-1][0-9])\-([0-3][0-9])T([0-5][0-9])\:([0-5][0-9])\:([0-5][0-9])(Z|([\-\+]([0-1][0-9])\:00))$/,
                date: /(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))$/,
                time: /^(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9]){2}$/,
                dateISO: /^\d{4}[\/\-]\d{1,2}[\/\-]\d{1,2}$/,
                month_day_year: /^(0[1-9]|1[012])[- \/.](0[1-9]|[12][0-9]|3[01])[- \/.]\d{4}$/,
                day_month_year: /^(0[1-9]|[12][0-9]|3[01])[- \/.](0[1-9]|1[012])[- \/.]\d{4}$/,
                color: /^#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/
            },
            validators: {
                equalTo: function(el, required, parent) {
                    var from = document.getElementById(el.getAttribute(this.add_namespace("data-equalto"))).value, to = el.value, valid = from === to;
                    return valid;
                }
            }
        },
        timer: null,
        init: function(scope, method, options) {
            this.bindings(method, options);
        },
        events: function(scope) {
            var self = this, form = self.S(scope).attr("novalidate", "novalidate"), settings = form.data(this.attr_name(true) + "-init") || {};
            this.invalid_attr = this.add_namespace("data-invalid");
            function validate(originalSelf, e) {
                clearTimeout(self.timer);
                self.timer = setTimeout(function() {
                    self.validate([ originalSelf ], e);
                }.bind(originalSelf), settings.timeout);
            }
            form.off(".abide").on("submit.fndtn.abide", function(e) {
                var is_ajax = /ajax/i.test(self.S(this).attr(self.attr_name()));
                return self.validate(self.S(this).find("input, textarea, select").not(":hidden, [data-abide-ignore]").get(), e, is_ajax);
            }).on("validate.fndtn.abide", function(e) {
                if (settings.validate_on === "manual") {
                    self.validate([ e.target ], e);
                }
            }).on("reset", function(e) {
                return self.reset($(this), e);
            }).find("input, textarea, select").not(":hidden, [data-abide-ignore]").off(".abide").on("blur.fndtn.abide change.fndtn.abide", function(e) {
                var id = this.getAttribute("id"), eqTo = form.find('[data-equalto="' + id + '"]');
                if (settings.validate_on_blur && settings.validate_on_blur === true) {
                    validate(this, e);
                }
                if (typeof eqTo.get(0) !== "undefined" && eqTo.val().length) {
                    validate(eqTo.get(0), e);
                }
                if (settings.validate_on === "change") {
                    validate(this, e);
                }
            }).on("keydown.fndtn.abide", function(e) {
                var id = this.getAttribute("id"), eqTo = form.find('[data-equalto="' + id + '"]');
                if (settings.live_validate && settings.live_validate === true && e.which != 9) {
                    validate(this, e);
                }
                if (typeof eqTo.get(0) !== "undefined" && eqTo.val().length) {
                    validate(eqTo.get(0), e);
                }
                if (settings.validate_on === "tab" && e.which === 9) {
                    validate(this, e);
                } else if (settings.validate_on === "change") {
                    validate(this, e);
                }
            }).on("focus", function(e) {
                if (navigator.userAgent.match(/iPad|iPhone|Android|BlackBerry|Windows Phone|webOS/i)) {
                    $("html, body").animate({
                        scrollTop: $(e.target).offset().top
                    }, 100);
                }
            });
        },
        reset: function(form, e) {
            var self = this;
            form.removeAttr(self.invalid_attr);
            $("[" + self.invalid_attr + "]", form).removeAttr(self.invalid_attr);
            $("." + self.settings.error_class, form).not("small").removeClass(self.settings.error_class);
            $(":input", form).not(":button, :submit, :reset, :hidden, [data-abide-ignore]").val("").removeAttr(self.invalid_attr);
        },
        validate: function(els, e, is_ajax) {
            var validations = this.parse_patterns(els), validation_count = validations.length, form = this.S(els[0]).closest("form"), submit_event = /submit/.test(e.type);
            for (var i = 0; i < validation_count; i++) {
                if (!validations[i] && (submit_event || is_ajax)) {
                    if (this.settings.focus_on_invalid) {
                        els[i].focus();
                    }
                    form.trigger("invalid.fndtn.abide");
                    this.S(els[i]).closest("form").attr(this.invalid_attr, "");
                    return false;
                }
            }
            if (submit_event || is_ajax) {
                form.trigger("valid.fndtn.abide");
            }
            form.removeAttr(this.invalid_attr);
            if (is_ajax) {
                return false;
            }
            return true;
        },
        parse_patterns: function(els) {
            var i = els.length, el_patterns = [];
            while (i--) {
                el_patterns.push(this.pattern(els[i]));
            }
            return this.check_validation_and_apply_styles(el_patterns);
        },
        pattern: function(el) {
            var type = el.getAttribute("type"), required = typeof el.getAttribute("required") === "string";
            var pattern = el.getAttribute("pattern") || "";
            if (this.settings.patterns.hasOwnProperty(pattern) && pattern.length > 0) {
                return [ el, this.settings.patterns[pattern], required ];
            } else if (pattern.length > 0) {
                return [ el, new RegExp(pattern), required ];
            }
            if (this.settings.patterns.hasOwnProperty(type)) {
                return [ el, this.settings.patterns[type], required ];
            }
            pattern = /.*/;
            return [ el, pattern, required ];
        },
        check_validation_and_apply_styles: function(el_patterns) {
            var i = el_patterns.length, validations = [];
            if (i == 0) {
                return validations;
            }
            var form = this.S(el_patterns[0][0]).closest("[data-" + this.attr_name(true) + "]"), settings = form.data(this.attr_name(true) + "-init") || {};
            while (i--) {
                var el = el_patterns[i][0], required = el_patterns[i][2], value = el.value.trim(), direct_parent = this.S(el).parent(), validator = el.getAttribute(this.add_namespace("data-abide-validator")), is_radio = el.type === "radio", is_checkbox = el.type === "checkbox", label = this.S('label[for="' + el.getAttribute("id") + '"]'), valid_length = required ? el.value.length > 0 : true, el_validations = [];
                var parent, valid;
                if (el.getAttribute(this.add_namespace("data-equalto"))) {
                    validator = "equalTo";
                }
                if (!direct_parent.is("label")) {
                    parent = direct_parent;
                } else {
                    parent = direct_parent.parent();
                }
                if (is_radio && required) {
                    el_validations.push(this.valid_radio(el, required));
                } else if (is_checkbox && required) {
                    el_validations.push(this.valid_checkbox(el, required));
                } else if (validator) {
                    var validators = validator.split(" ");
                    var last_valid = true, all_valid = true;
                    for (var iv = 0; iv < validators.length; iv++) {
                        valid = this.settings.validators[validators[iv]].apply(this, [ el, required, parent ]);
                        el_validations.push(valid);
                        all_valid = valid && last_valid;
                        last_valid = valid;
                    }
                    if (all_valid) {
                        this.S(el).removeAttr(this.invalid_attr);
                        parent.removeClass("error");
                        if (label.length > 0 && this.settings.error_labels) {
                            label.removeClass(this.settings.error_class).removeAttr("role");
                        }
                        $(el).triggerHandler("valid");
                    } else {
                        this.S(el).attr(this.invalid_attr, "");
                        parent.addClass("error");
                        if (label.length > 0 && this.settings.error_labels) {
                            label.addClass(this.settings.error_class).attr("role", "alert");
                        }
                        $(el).triggerHandler("invalid");
                    }
                } else {
                    if (el_patterns[i][1].test(value) && valid_length || !required && el.value.length < 1 || $(el).attr("disabled")) {
                        el_validations.push(true);
                    } else {
                        el_validations.push(false);
                    }
                    el_validations = [ el_validations.every(function(valid) {
                        return valid;
                    }) ];
                    if (el_validations[0]) {
                        this.S(el).removeAttr(this.invalid_attr);
                        el.setAttribute("aria-invalid", "false");
                        el.removeAttribute("aria-describedby");
                        parent.removeClass(this.settings.error_class);
                        if (label.length > 0 && this.settings.error_labels) {
                            label.removeClass(this.settings.error_class).removeAttr("role");
                        }
                        $(el).triggerHandler("valid");
                    } else {
                        this.S(el).attr(this.invalid_attr, "");
                        el.setAttribute("aria-invalid", "true");
                        var errorElem = parent.find("small." + this.settings.error_class, "span." + this.settings.error_class);
                        var errorID = errorElem.length > 0 ? errorElem[0].id : "";
                        if (errorID.length > 0) {
                            el.setAttribute("aria-describedby", errorID);
                        }
                        parent.addClass(this.settings.error_class);
                        if (label.length > 0 && this.settings.error_labels) {
                            label.addClass(this.settings.error_class).attr("role", "alert");
                        }
                        $(el).triggerHandler("invalid");
                    }
                }
                validations = validations.concat(el_validations);
            }
            return validations;
        },
        valid_checkbox: function(el, required) {
            var el = this.S(el), valid = el.is(":checked") || !required || el.get(0).getAttribute("disabled");
            if (valid) {
                el.removeAttr(this.invalid_attr).parent().removeClass(this.settings.error_class);
                $(el).triggerHandler("valid");
            } else {
                el.attr(this.invalid_attr, "").parent().addClass(this.settings.error_class);
                $(el).triggerHandler("invalid");
            }
            return valid;
        },
        valid_radio: function(el, required) {
            var name = el.getAttribute("name"), group = this.S(el).closest("[data-" + this.attr_name(true) + "]").find("[name='" + name + "']"), count = group.length, valid = false, disabled = false;
            for (var i = 0; i < count; i++) {
                if (group[i].getAttribute("disabled")) {
                    disabled = true;
                    valid = true;
                } else {
                    if (group[i].checked) {
                        valid = true;
                    } else {
                        if (disabled) {
                            valid = false;
                        }
                    }
                }
            }
            for (var i = 0; i < count; i++) {
                if (valid) {
                    this.S(group[i]).removeAttr(this.invalid_attr).parent().removeClass(this.settings.error_class);
                    $(group[i]).triggerHandler("valid");
                } else {
                    this.S(group[i]).attr(this.invalid_attr, "").parent().addClass(this.settings.error_class);
                    $(group[i]).triggerHandler("invalid");
                }
            }
            return valid;
        },
        valid_equal: function(el, required, parent) {
            var from = document.getElementById(el.getAttribute(this.add_namespace("data-equalto"))).value, to = el.value, valid = from === to;
            if (valid) {
                this.S(el).removeAttr(this.invalid_attr);
                parent.removeClass(this.settings.error_class);
                if (label.length > 0 && settings.error_labels) {
                    label.removeClass(this.settings.error_class);
                }
            } else {
                this.S(el).attr(this.invalid_attr, "");
                parent.addClass(this.settings.error_class);
                if (label.length > 0 && settings.error_labels) {
                    label.addClass(this.settings.error_class);
                }
            }
            return valid;
        },
        valid_oneof: function(el, required, parent, doNotValidateOthers) {
            var el = this.S(el), others = this.S("[" + this.add_namespace("data-oneof") + "]"), valid = others.filter(":checked").length > 0;
            if (valid) {
                el.removeAttr(this.invalid_attr).parent().removeClass(this.settings.error_class);
            } else {
                el.attr(this.invalid_attr, "").parent().addClass(this.settings.error_class);
            }
            if (!doNotValidateOthers) {
                var _this = this;
                others.each(function() {
                    _this.valid_oneof.call(_this, this, null, null, true);
                });
            }
            return valid;
        },
        reflow: function(scope, options) {
            var self = this, form = self.S("[" + this.attr_name() + "]").attr("novalidate", "novalidate");
            self.S(form).each(function(idx, el) {
                self.events(el);
            });
        }
    };
})(jQuery, window, window.document);

(function($, window, document, undefined) {
    "use strict";
    Foundation.libs.accordion = {
        name: "accordion",
        version: "5.5.3",
        settings: {
            content_class: "content",
            active_class: "active",
            multi_expand: false,
            toggleable: true,
            callback: function() {}
        },
        init: function(scope, method, options) {
            this.bindings(method, options);
        },
        events: function(instance) {
            var self = this;
            var S = this.S;
            self.create(this.S(instance));
            S(this.scope).off(".fndtn.accordion").on("click.fndtn.accordion", "[" + this.attr_name() + "] > dd > a, [" + this.attr_name() + "] > li > a", function(e) {
                var accordion = S(this).closest("[" + self.attr_name() + "]"), groupSelector = self.attr_name() + "=" + accordion.attr(self.attr_name()), settings = accordion.data(self.attr_name(true) + "-init") || self.settings, target = S("#" + this.href.split("#")[1]), aunts = $("> dd, > li", accordion), siblings = aunts.children("." + settings.content_class), active_content = siblings.filter("." + settings.active_class);
                e.preventDefault();
                if (accordion.attr(self.attr_name())) {
                    siblings = siblings.add("[" + groupSelector + "] dd > " + "." + settings.content_class + ", [" + groupSelector + "] li > " + "." + settings.content_class);
                    aunts = aunts.add("[" + groupSelector + "] dd, [" + groupSelector + "] li");
                }
                if (settings.toggleable && target.is(active_content)) {
                    target.parent("dd, li").toggleClass(settings.active_class, false);
                    target.toggleClass(settings.active_class, false);
                    S(this).attr("aria-expanded", function(i, attr) {
                        return attr === "true" ? "false" : "true";
                    });
                    settings.callback(target);
                    target.triggerHandler("toggled", [ accordion ]);
                    accordion.triggerHandler("toggled", [ target ]);
                    return;
                }
                if (!settings.multi_expand) {
                    siblings.removeClass(settings.active_class);
                    aunts.removeClass(settings.active_class);
                    aunts.children("a").attr("aria-expanded", "false");
                }
                target.addClass(settings.active_class).parent().addClass(settings.active_class);
                settings.callback(target);
                target.triggerHandler("toggled", [ accordion ]);
                accordion.triggerHandler("toggled", [ target ]);
                S(this).attr("aria-expanded", "true");
            });
        },
        create: function($instance) {
            var self = this, accordion = $instance, aunts = $("> .accordion-navigation", accordion), settings = accordion.data(self.attr_name(true) + "-init") || self.settings;
            aunts.children("a").attr("aria-expanded", "false");
            aunts.has("." + settings.content_class + "." + settings.active_class).addClass(settings.active_class).children("a").attr("aria-expanded", "true");
            if (settings.multi_expand) {
                $instance.attr("aria-multiselectable", "true");
            }
        },
        toggle: function(options) {
            var options = typeof options !== "undefined" ? options : {};
            var selector = typeof options.selector !== "undefined" ? options.selector : "";
            var toggle_state = typeof options.toggle_state !== "undefined" ? options.toggle_state : "";
            var $accordion = typeof options.$accordion !== "undefined" ? options.$accordion : this.S(this.scope).closest("[" + this.attr_name() + "]");
            var $items = $accordion.find("> dd" + selector + ", > li" + selector);
            if ($items.length < 1) {
                if (window.console) {
                    console.error("Selection not found.", selector);
                }
                return false;
            }
            var S = this.S;
            var active_class = this.settings.active_class;
            $items.each(function() {
                var $item = S(this);
                var is_active = $item.hasClass(active_class);
                if (is_active && toggle_state === "close" || !is_active && toggle_state === "open" || toggle_state === "") {
                    $item.find("> a").trigger("click.fndtn.accordion");
                }
            });
        },
        open: function(options) {
            var options = typeof options !== "undefined" ? options : {};
            options.toggle_state = "open";
            this.toggle(options);
        },
        close: function(options) {
            var options = typeof options !== "undefined" ? options : {};
            options.toggle_state = "close";
            this.toggle(options);
        },
        off: function() {},
        reflow: function() {}
    };
})(jQuery, window, window.document);

(function($, window, document, undefined) {
    "use strict";
    Foundation.libs.alert = {
        name: "alert",
        version: "5.5.3",
        settings: {
            callback: function() {}
        },
        init: function(scope, method, options) {
            this.bindings(method, options);
        },
        events: function() {
            var self = this, S = this.S;
            $(this.scope).off(".alert").on("click.fndtn.alert", "[" + this.attr_name() + "] .close", function(e) {
                var alertBox = S(this).closest("[" + self.attr_name() + "]"), settings = alertBox.data(self.attr_name(true) + "-init") || self.settings;
                e.preventDefault();
                if (Modernizr.csstransitions) {
                    alertBox.addClass("alert-close");
                    alertBox.on("transitionend webkitTransitionEnd oTransitionEnd", function(e) {
                        S(this).trigger("close.fndtn.alert").remove();
                        settings.callback();
                    });
                } else {
                    alertBox.fadeOut(300, function() {
                        S(this).trigger("close.fndtn.alert").remove();
                        settings.callback();
                    });
                }
            });
        },
        reflow: function() {}
    };
})(jQuery, window, window.document);

(function($, window, document, undefined) {
    "use strict";
    Foundation.libs.clearing = {
        name: "clearing",
        version: "5.5.3",
        settings: {
            templates: {
                viewing: '<a href="#" class="clearing-close">&times;</a>' + '<div class="visible-img" style="display: none"><div class="clearing-touch-label"></div><img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs%3D" alt="" />' + '<p class="clearing-caption"></p><a href="#" class="clearing-main-prev"><span></span></a>' + '<a href="#" class="clearing-main-next"><span></span></a></div>' + '<img class="clearing-preload-next" style="display: none" src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs%3D" alt="" />' + '<img class="clearing-preload-prev" style="display: none" src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs%3D" alt="" />'
            },
            close_selectors: ".clearing-close, div.clearing-blackout",
            open_selectors: "",
            skip_selector: "",
            touch_label: "",
            init: false,
            locked: false
        },
        init: function(scope, method, options) {
            var self = this;
            Foundation.inherit(this, "throttle image_loaded");
            this.bindings(method, options);
            if (self.S(this.scope).is("[" + this.attr_name() + "]")) {
                this.assemble(self.S("li", this.scope));
            } else {
                self.S("[" + this.attr_name() + "]", this.scope).each(function() {
                    self.assemble(self.S("li", this));
                });
            }
        },
        events: function(scope) {
            var self = this, S = self.S, $scroll_container = $(".scroll-container");
            if ($scroll_container.length > 0) {
                this.scope = $scroll_container;
            }
            S(this.scope).off(".clearing").on("click.fndtn.clearing", "ul[" + this.attr_name() + "] li " + this.settings.open_selectors, function(e, current, target) {
                var current = current || S(this), target = target || current, next = current.next("li"), settings = current.closest("[" + self.attr_name() + "]").data(self.attr_name(true) + "-init"), image = S(e.target);
                e.preventDefault();
                if (!settings) {
                    self.init();
                    settings = current.closest("[" + self.attr_name() + "]").data(self.attr_name(true) + "-init");
                }
                if (target.hasClass("visible") && current[0] === target[0] && next.length > 0 && self.is_open(current)) {
                    target = next;
                    image = S("img", target);
                }
                self.open(image, current, target);
                self.update_paddles(target);
            }).on("click.fndtn.clearing", ".clearing-main-next", function(e) {
                self.nav(e, "next");
            }).on("click.fndtn.clearing", ".clearing-main-prev", function(e) {
                self.nav(e, "prev");
            }).on("click.fndtn.clearing", this.settings.close_selectors, function(e) {
                Foundation.libs.clearing.close(e, this);
            });
            $(document).on("keydown.fndtn.clearing", function(e) {
                self.keydown(e);
            });
            S(window).off(".clearing").on("resize.fndtn.clearing", function() {
                self.resize();
            });
            this.swipe_events(scope);
        },
        swipe_events: function(scope) {
            var self = this, S = self.S;
            S(this.scope).on("touchstart.fndtn.clearing", ".visible-img", function(e) {
                if (!e.touches) {
                    e = e.originalEvent;
                }
                var data = {
                    start_page_x: e.touches[0].pageX,
                    start_page_y: e.touches[0].pageY,
                    start_time: new Date().getTime(),
                    delta_x: 0,
                    is_scrolling: undefined
                };
                S(this).data("swipe-transition", data);
                e.stopPropagation();
            }).on("touchmove.fndtn.clearing", ".visible-img", function(e) {
                if (!e.touches) {
                    e = e.originalEvent;
                }
                if (e.touches.length > 1 || e.scale && e.scale !== 1) {
                    return;
                }
                var data = S(this).data("swipe-transition");
                if (typeof data === "undefined") {
                    data = {};
                }
                data.delta_x = e.touches[0].pageX - data.start_page_x;
                if (Foundation.rtl) {
                    data.delta_x = -data.delta_x;
                }
                if (typeof data.is_scrolling === "undefined") {
                    data.is_scrolling = !!(data.is_scrolling || Math.abs(data.delta_x) < Math.abs(e.touches[0].pageY - data.start_page_y));
                }
                if (!data.is_scrolling && !data.active) {
                    e.preventDefault();
                    var direction = data.delta_x < 0 ? "next" : "prev";
                    data.active = true;
                    self.nav(e, direction);
                }
            }).on("touchend.fndtn.clearing", ".visible-img", function(e) {
                S(this).data("swipe-transition", {});
                e.stopPropagation();
            });
        },
        assemble: function($li) {
            var $el = $li.parent();
            if ($el.parent().hasClass("carousel")) {
                return;
            }
            $el.after('<div id="foundationClearingHolder"></div>');
            var grid = $el.detach(), grid_outerHTML = "";
            if (grid[0] == null) {
                return;
            } else {
                grid_outerHTML = grid[0].outerHTML;
            }
            var holder = this.S("#foundationClearingHolder"), settings = $el.data(this.attr_name(true) + "-init"), data = {
                grid: '<div class="carousel">' + grid_outerHTML + "</div>",
                viewing: settings.templates.viewing
            }, wrapper = '<div class="clearing-assembled"><div>' + data.viewing + data.grid + "</div></div>", touch_label = this.settings.touch_label;
            if (Modernizr.touch) {
                wrapper = $(wrapper).find(".clearing-touch-label").html(touch_label).end();
            }
            holder.after(wrapper).remove();
        },
        open: function($image, current, target) {
            var self = this, body = $(document.body), root = target.closest(".clearing-assembled"), container = self.S("div", root).first(), visible_image = self.S(".visible-img", container), image = self.S("img", visible_image).not($image), label = self.S(".clearing-touch-label", container), error = false, loaded = {};
            $("body").on("touchmove", function(e) {
                e.preventDefault();
            });
            image.error(function() {
                error = true;
            });
            function startLoad() {
                setTimeout(function() {
                    this.image_loaded(image, function() {
                        if (image.outerWidth() === 1 && !error) {
                            startLoad.call(this);
                        } else {
                            cb.call(this, image);
                        }
                    }.bind(this));
                }.bind(this), 100);
            }
            function cb(image) {
                var $image = $(image);
                $image.css("visibility", "visible");
                $image.trigger("imageVisible");
                body.css("overflow", "hidden");
                root.addClass("clearing-blackout");
                container.addClass("clearing-container");
                visible_image.show();
                this.fix_height(target).caption(self.S(".clearing-caption", visible_image), self.S("img", target)).center_and_label(image, label).shift(current, target, function() {
                    target.closest("li").siblings().removeClass("visible");
                    target.closest("li").addClass("visible");
                });
                visible_image.trigger("opened.fndtn.clearing");
            }
            if (!this.locked()) {
                visible_image.trigger("open.fndtn.clearing");
                loaded = this.load($image);
                if (loaded.interchange) {
                    image.attr("data-interchange", loaded.interchange).foundation("interchange", "reflow");
                } else {
                    image.attr("src", loaded.src).attr("data-interchange", "");
                }
                image.css("visibility", "hidden");
                startLoad.call(this);
            }
        },
        close: function(e, el) {
            e.preventDefault();
            var root = function(target) {
                if (/blackout/.test(target.selector)) {
                    return target;
                } else {
                    return target.closest(".clearing-blackout");
                }
            }($(el)), body = $(document.body), container, visible_image;
            if (el === e.target && root) {
                body.css("overflow", "");
                container = $("div", root).first();
                visible_image = $(".visible-img", container);
                visible_image.trigger("close.fndtn.clearing");
                this.settings.prev_index = 0;
                $("ul[" + this.attr_name() + "]", root).attr("style", "").closest(".clearing-blackout").removeClass("clearing-blackout");
                container.removeClass("clearing-container");
                visible_image.hide();
                visible_image.trigger("closed.fndtn.clearing");
            }
            $("body").off("touchmove");
            return false;
        },
        is_open: function(current) {
            return current.parent().prop("style").length > 0;
        },
        keydown: function(e) {
            var clearing = $(".clearing-blackout ul[" + this.attr_name() + "]"), NEXT_KEY = this.rtl ? 37 : 39, PREV_KEY = this.rtl ? 39 : 37, ESC_KEY = 27;
            if (e.which === NEXT_KEY) {
                this.go(clearing, "next");
            }
            if (e.which === PREV_KEY) {
                this.go(clearing, "prev");
            }
            if (e.which === ESC_KEY) {
                this.S("a.clearing-close").trigger("click.fndtn.clearing");
            }
        },
        nav: function(e, direction) {
            var clearing = $("ul[" + this.attr_name() + "]", ".clearing-blackout");
            e.preventDefault();
            this.go(clearing, direction);
        },
        resize: function() {
            var image = $("img", ".clearing-blackout .visible-img"), label = $(".clearing-touch-label", ".clearing-blackout");
            if (image.length) {
                this.center_and_label(image, label);
                image.trigger("resized.fndtn.clearing");
            }
        },
        fix_height: function(target) {
            var lis = target.parent().children(), self = this;
            lis.each(function() {
                var li = self.S(this), image = li.find("img");
                if (li.height() > image.outerHeight()) {
                    li.addClass("fix-height");
                }
            }).closest("ul").width(lis.length * 100 + "%");
            return this;
        },
        update_paddles: function(target) {
            target = target.closest("li");
            var visible_image = target.closest(".carousel").siblings(".visible-img");
            if (target.next().length > 0) {
                this.S(".clearing-main-next", visible_image).removeClass("disabled");
            } else {
                this.S(".clearing-main-next", visible_image).addClass("disabled");
            }
            if (target.prev().length > 0) {
                this.S(".clearing-main-prev", visible_image).removeClass("disabled");
            } else {
                this.S(".clearing-main-prev", visible_image).addClass("disabled");
            }
        },
        center_and_label: function(target, label) {
            if (!this.rtl && label.length > 0) {
                label.css({
                    marginLeft: -(label.outerWidth() / 2),
                    marginTop: -(target.outerHeight() / 2) - label.outerHeight() - 10
                });
            } else {
                label.css({
                    marginRight: -(label.outerWidth() / 2),
                    marginTop: -(target.outerHeight() / 2) - label.outerHeight() - 10,
                    left: "auto",
                    right: "50%"
                });
            }
            return this;
        },
        load: function($image) {
            var href, interchange, closest_a;
            if ($image[0].nodeName === "A") {
                href = $image.attr("href");
                interchange = $image.data("clearing-interchange");
            } else {
                closest_a = $image.closest("a");
                href = closest_a.attr("href");
                interchange = closest_a.data("clearing-interchange");
            }
            this.preload($image);
            return {
                src: href ? href : $image.attr("src"),
                interchange: href ? interchange : $image.data("clearing-interchange")
            };
        },
        preload: function($image) {
            this.img($image.closest("li").next(), "next").img($image.closest("li").prev(), "prev");
        },
        img: function(img, sibling_type) {
            if (img.length) {
                var preload_img = $(".clearing-preload-" + sibling_type), new_a = this.S("a", img), src, interchange, image;
                if (new_a.length) {
                    src = new_a.attr("href");
                    interchange = new_a.data("clearing-interchange");
                } else {
                    image = this.S("img", img);
                    src = image.attr("src");
                    interchange = image.data("clearing-interchange");
                }
                if (interchange) {
                    preload_img.attr("data-interchange", interchange);
                } else {
                    preload_img.attr("src", src);
                    preload_img.attr("data-interchange", "");
                }
            }
            return this;
        },
        caption: function(container, $image) {
            var caption = $image.attr("data-caption");
            if (caption) {
                var containerPlain = container.get(0);
                containerPlain.innerHTML = caption;
                container.show();
            } else {
                container.text("").hide();
            }
            return this;
        },
        go: function($ul, direction) {
            var current = this.S(".visible", $ul), target = current[direction]();
            if (this.settings.skip_selector && target.find(this.settings.skip_selector).length != 0) {
                target = target[direction]();
            }
            if (target.length) {
                this.S("img", target).trigger("click.fndtn.clearing", [ current, target ]).trigger("change.fndtn.clearing");
            }
        },
        shift: function(current, target, callback) {
            var clearing = target.parent(), old_index = this.settings.prev_index || target.index(), direction = this.direction(clearing, current, target), dir = this.rtl ? "right" : "left", left = parseInt(clearing.css("left"), 10), width = target.outerWidth(), skip_shift;
            var dir_obj = {};
            if (target.index() !== old_index && !/skip/.test(direction)) {
                if (/left/.test(direction)) {
                    this.lock();
                    dir_obj[dir] = left + width;
                    clearing.animate(dir_obj, 300, this.unlock());
                } else if (/right/.test(direction)) {
                    this.lock();
                    dir_obj[dir] = left - width;
                    clearing.animate(dir_obj, 300, this.unlock());
                }
            } else if (/skip/.test(direction)) {
                skip_shift = target.index() - this.settings.up_count;
                this.lock();
                if (skip_shift > 0) {
                    dir_obj[dir] = -(skip_shift * width);
                    clearing.animate(dir_obj, 300, this.unlock());
                } else {
                    dir_obj[dir] = 0;
                    clearing.animate(dir_obj, 300, this.unlock());
                }
            }
            callback();
        },
        direction: function($el, current, target) {
            var lis = this.S("li", $el), li_width = lis.outerWidth() + lis.outerWidth() / 4, up_count = Math.floor(this.S(".clearing-container").outerWidth() / li_width) - 1, target_index = lis.index(target), response;
            this.settings.up_count = up_count;
            if (this.adjacent(this.settings.prev_index, target_index)) {
                if (target_index > up_count && target_index > this.settings.prev_index) {
                    response = "right";
                } else if (target_index > up_count - 1 && target_index <= this.settings.prev_index) {
                    response = "left";
                } else {
                    response = false;
                }
            } else {
                response = "skip";
            }
            this.settings.prev_index = target_index;
            return response;
        },
        adjacent: function(current_index, target_index) {
            for (var i = target_index + 1; i >= target_index - 1; i--) {
                if (i === current_index) {
                    return true;
                }
            }
            return false;
        },
        lock: function() {
            this.settings.locked = true;
        },
        unlock: function() {
            this.settings.locked = false;
        },
        locked: function() {
            return this.settings.locked;
        },
        off: function() {
            this.S(this.scope).off(".fndtn.clearing");
            this.S(window).off(".fndtn.clearing");
        },
        reflow: function() {
            this.init();
        }
    };
})(jQuery, window, window.document);

(function($, window, document, undefined) {
    "use strict";
    Foundation.libs.dropdown = {
        name: "dropdown",
        version: "5.5.3",
        settings: {
            active_class: "open",
            disabled_class: "disabled",
            mega_class: "mega",
            align: "bottom",
            is_hover: false,
            hover_timeout: 150,
            opened: function() {},
            closed: function() {}
        },
        init: function(scope, method, options) {
            Foundation.inherit(this, "throttle");
            $.extend(true, this.settings, method, options);
            this.bindings(method, options);
        },
        events: function(scope) {
            var self = this, S = self.S;
            S(this.scope).off(".dropdown").on("click.fndtn.dropdown", "[" + this.attr_name() + "]", function(e) {
                var settings = S(this).data(self.attr_name(true) + "-init") || self.settings;
                if (!settings.is_hover || Modernizr.touch) {
                    e.preventDefault();
                    if (S(this).parent("[data-reveal-id]").length) {
                        e.stopPropagation();
                    }
                    self.toggle($(this));
                }
            }).on("mouseenter.fndtn.dropdown", "[" + this.attr_name() + "], [" + this.attr_name() + "-content]", function(e) {
                var $this = S(this), dropdown, target;
                clearTimeout(self.timeout);
                if ($this.data(self.data_attr())) {
                    dropdown = S("#" + $this.data(self.data_attr()));
                    target = $this;
                } else {
                    dropdown = $this;
                    target = S("[" + self.attr_name() + '="' + dropdown.attr("id") + '"]');
                }
                var settings = target.data(self.attr_name(true) + "-init") || self.settings;
                if (S(e.currentTarget).data(self.data_attr()) && settings.is_hover) {
                    self.closeall.call(self);
                }
                if (settings.is_hover) {
                    self.open.apply(self, [ dropdown, target ]);
                }
            }).on("mouseleave.fndtn.dropdown", "[" + this.attr_name() + "], [" + this.attr_name() + "-content]", function(e) {
                var $this = S(this);
                var settings;
                if ($this.data(self.data_attr())) {
                    settings = $this.data(self.data_attr(true) + "-init") || self.settings;
                } else {
                    var target = S("[" + self.attr_name() + '="' + S(this).attr("id") + '"]'), settings = target.data(self.attr_name(true) + "-init") || self.settings;
                }
                self.timeout = setTimeout(function() {
                    if ($this.data(self.data_attr())) {
                        if (settings.is_hover) {
                            self.close.call(self, S("#" + $this.data(self.data_attr())));
                        }
                    } else {
                        if (settings.is_hover) {
                            self.close.call(self, $this);
                        }
                    }
                }.bind(this), settings.hover_timeout);
            }).on("click.fndtn.dropdown", function(e) {
                var parent = S(e.target).closest("[" + self.attr_name() + "-content]");
                var links = parent.find("a");
                if (links.length > 0 && parent.attr("aria-autoclose") !== "false") {
                    self.close.call(self, S("[" + self.attr_name() + "-content]"));
                }
                if (e.target !== document && !$.contains(document.documentElement, e.target)) {
                    return;
                }
                if (S(e.target).closest("[" + self.attr_name() + "]").length > 0) {
                    return;
                }
                if (!S(e.target).data("revealId") && (parent.length > 0 && (S(e.target).is("[" + self.attr_name() + "-content]") || $.contains(parent.first()[0], e.target)))) {
                    e.stopPropagation();
                    return;
                }
                self.close.call(self, S("[" + self.attr_name() + "-content]"));
            }).on("opened.fndtn.dropdown", "[" + self.attr_name() + "-content]", function() {
                self.settings.opened.call(this);
            }).on("closed.fndtn.dropdown", "[" + self.attr_name() + "-content]", function() {
                self.settings.closed.call(this);
            });
            S(window).off(".dropdown").on("resize.fndtn.dropdown", self.throttle(function() {
                self.resize.call(self);
            }, 50));
            this.resize();
        },
        close: function(dropdown) {
            var self = this;
            dropdown.each(function(idx) {
                var original_target = $("[" + self.attr_name() + "=" + dropdown[idx].id + "]") || $("aria-controls=" + dropdown[idx].id + "]");
                original_target.attr("aria-expanded", "false");
                if (self.S(this).hasClass(self.settings.active_class)) {
                    self.S(this).css(Foundation.rtl ? "right" : "left", "-99999px").attr("aria-hidden", "true").removeClass(self.settings.active_class).prev("[" + self.attr_name() + "]").removeClass(self.settings.active_class).removeData("target");
                    self.S(this).trigger("closed.fndtn.dropdown", [ dropdown ]);
                }
            });
            dropdown.removeClass("f-open-" + this.attr_name(true));
        },
        closeall: function() {
            var self = this;
            $.each(self.S(".f-open-" + this.attr_name(true)), function() {
                self.close.call(self, self.S(this));
            });
        },
        open: function(dropdown, target) {
            this.css(dropdown.addClass(this.settings.active_class), target);
            dropdown.prev("[" + this.attr_name() + "]").addClass(this.settings.active_class);
            dropdown.data("target", target.get(0)).trigger("opened.fndtn.dropdown", [ dropdown, target ]);
            dropdown.attr("aria-hidden", "false");
            target.attr("aria-expanded", "true");
            dropdown.focus();
            dropdown.addClass("f-open-" + this.attr_name(true));
        },
        data_attr: function() {
            if (this.namespace.length > 0) {
                return this.namespace + "-" + this.name;
            }
            return this.name;
        },
        toggle: function(target) {
            if (target.hasClass(this.settings.disabled_class)) {
                return;
            }
            var dropdown = this.S("#" + target.data(this.data_attr()));
            if (dropdown.length === 0) {
                return;
            }
            this.close.call(this, this.S("[" + this.attr_name() + "-content]").not(dropdown));
            if (dropdown.hasClass(this.settings.active_class)) {
                this.close.call(this, dropdown);
                if (dropdown.data("target") !== target.get(0)) {
                    this.open.call(this, dropdown, target);
                }
            } else {
                this.open.call(this, dropdown, target);
            }
        },
        resize: function() {
            var dropdown = this.S("[" + this.attr_name() + "-content].open");
            var target = $(dropdown.data("target"));
            if (dropdown.length && target.length) {
                this.css(dropdown, target);
            }
        },
        css: function(dropdown, target) {
            var left_offset = Math.max((target.width() - dropdown.width()) / 2, 8), settings = target.data(this.attr_name(true) + "-init") || this.settings, parentOverflow = dropdown.parent().css("overflow-y") || dropdown.parent().css("overflow");
            this.clear_idx();
            if (this.small()) {
                var p = this.dirs.bottom.call(dropdown, target, settings);
                dropdown.attr("style", "").removeClass("drop-left drop-right drop-top").css({
                    position: "absolute",
                    width: "95%",
                    "max-width": "none",
                    top: p.top
                });
                dropdown.css(Foundation.rtl ? "right" : "left", left_offset);
            } else if (parentOverflow !== "visible") {
                var offset = target[0].offsetTop + target[0].offsetHeight;
                dropdown.attr("style", "").css({
                    position: "absolute",
                    top: offset
                });
                dropdown.css(Foundation.rtl ? "right" : "left", left_offset);
            } else {
                this.style(dropdown, target, settings);
            }
            return dropdown;
        },
        style: function(dropdown, target, settings) {
            var css = $.extend({
                position: "absolute"
            }, this.dirs[settings.align].call(dropdown, target, settings));
            dropdown.attr("style", "").css(css);
        },
        dirs: {
            _base: function(t, s) {
                var o_p = this.offsetParent(), o = o_p.offset(), p = t.offset();
                p.top -= o.top;
                p.left -= o.left;
                p.missRight = false;
                p.missTop = false;
                p.missLeft = false;
                p.leftRightFlag = false;
                var actualBodyWidth;
                var windowWidth = window.innerWidth;
                if (document.getElementsByClassName("row")[0]) {
                    actualBodyWidth = document.getElementsByClassName("row")[0].clientWidth;
                } else {
                    actualBodyWidth = windowWidth;
                }
                var actualMarginWidth = (windowWidth - actualBodyWidth) / 2;
                var actualBoundary = actualBodyWidth;
                if (!this.hasClass("mega") && !s.ignore_repositioning) {
                    var outerWidth = this.outerWidth();
                    var o_left = t.offset().left;
                    if (t.offset().top <= this.outerHeight()) {
                        p.missTop = true;
                        actualBoundary = windowWidth - actualMarginWidth;
                        p.leftRightFlag = true;
                    }
                    if (o_left + outerWidth > o_left + actualMarginWidth && o_left - actualMarginWidth > outerWidth) {
                        p.missRight = true;
                        p.missLeft = false;
                    }
                    if (o_left - outerWidth <= 0) {
                        p.missLeft = true;
                        p.missRight = false;
                    }
                }
                return p;
            },
            top: function(t, s) {
                var self = Foundation.libs.dropdown, p = self.dirs._base.call(this, t, s);
                this.addClass("drop-top");
                if (p.missTop == true) {
                    p.top = p.top + t.outerHeight() + this.outerHeight();
                    this.removeClass("drop-top");
                }
                if (p.missRight == true) {
                    p.left = p.left - this.outerWidth() + t.outerWidth();
                }
                if (t.outerWidth() < this.outerWidth() || self.small() || this.hasClass(s.mega_menu)) {
                    self.adjust_pip(this, t, s, p);
                }
                if (Foundation.rtl) {
                    return {
                        left: p.left - this.outerWidth() + t.outerWidth(),
                        top: p.top - this.outerHeight()
                    };
                }
                return {
                    left: p.left,
                    top: p.top - this.outerHeight()
                };
            },
            bottom: function(t, s) {
                var self = Foundation.libs.dropdown, p = self.dirs._base.call(this, t, s);
                if (p.missRight == true) {
                    p.left = p.left - this.outerWidth() + t.outerWidth();
                }
                if (t.outerWidth() < this.outerWidth() || self.small() || this.hasClass(s.mega_menu)) {
                    self.adjust_pip(this, t, s, p);
                }
                if (self.rtl) {
                    return {
                        left: p.left - this.outerWidth() + t.outerWidth(),
                        top: p.top + t.outerHeight()
                    };
                }
                return {
                    left: p.left,
                    top: p.top + t.outerHeight()
                };
            },
            left: function(t, s) {
                var p = Foundation.libs.dropdown.dirs._base.call(this, t, s);
                this.addClass("drop-left");
                if (p.missLeft == true) {
                    p.left = p.left + this.outerWidth();
                    p.top = p.top + t.outerHeight();
                    this.removeClass("drop-left");
                }
                return {
                    left: p.left - this.outerWidth(),
                    top: p.top
                };
            },
            right: function(t, s) {
                var p = Foundation.libs.dropdown.dirs._base.call(this, t, s);
                this.addClass("drop-right");
                if (p.missRight == true) {
                    p.left = p.left - this.outerWidth();
                    p.top = p.top + t.outerHeight();
                    this.removeClass("drop-right");
                } else {
                    p.triggeredRight = true;
                }
                var self = Foundation.libs.dropdown;
                if (t.outerWidth() < this.outerWidth() || self.small() || this.hasClass(s.mega_menu)) {
                    self.adjust_pip(this, t, s, p);
                }
                return {
                    left: p.left + t.outerWidth(),
                    top: p.top
                };
            }
        },
        adjust_pip: function(dropdown, target, settings, position) {
            var sheet = Foundation.stylesheet, pip_offset_base = 8;
            if (dropdown.hasClass(settings.mega_class)) {
                pip_offset_base = position.left + target.outerWidth() / 2 - 8;
            } else if (this.small()) {
                pip_offset_base += position.left - 8;
            }
            this.rule_idx = sheet.cssRules.length;
            var sel_before = ".f-dropdown.open:before", sel_after = ".f-dropdown.open:after", css_before = "left: " + pip_offset_base + "px;", css_after = "left: " + (pip_offset_base - 1) + "px;";
            if (position.missRight == true) {
                pip_offset_base = dropdown.outerWidth() - 23;
                sel_before = ".f-dropdown.open:before", sel_after = ".f-dropdown.open:after", css_before = "left: " + pip_offset_base + "px;", 
                css_after = "left: " + (pip_offset_base - 1) + "px;";
            }
            if (position.triggeredRight == true) {
                sel_before = ".f-dropdown.open:before", sel_after = ".f-dropdown.open:after", css_before = "left:-12px;", 
                css_after = "left:-14px;";
            }
            if (sheet.insertRule) {
                sheet.insertRule([ sel_before, "{", css_before, "}" ].join(" "), this.rule_idx);
                sheet.insertRule([ sel_after, "{", css_after, "}" ].join(" "), this.rule_idx + 1);
            } else {
                sheet.addRule(sel_before, css_before, this.rule_idx);
                sheet.addRule(sel_after, css_after, this.rule_idx + 1);
            }
        },
        clear_idx: function() {
            var sheet = Foundation.stylesheet;
            if (typeof this.rule_idx !== "undefined") {
                sheet.deleteRule(this.rule_idx);
                sheet.deleteRule(this.rule_idx);
                delete this.rule_idx;
            }
        },
        small: function() {
            return matchMedia(Foundation.media_queries.small).matches && !matchMedia(Foundation.media_queries.medium).matches;
        },
        off: function() {
            this.S(this.scope).off(".fndtn.dropdown");
            this.S("html, body").off(".fndtn.dropdown");
            this.S(window).off(".fndtn.dropdown");
            this.S("[data-dropdown-content]").off(".fndtn.dropdown");
        },
        reflow: function() {}
    };
})(jQuery, window, window.document);

(function($, window, document, undefined) {
    "use strict";
    Foundation.libs.equalizer = {
        name: "equalizer",
        version: "5.5.3",
        settings: {
            use_tallest: true,
            before_height_change: $.noop,
            after_height_change: $.noop,
            equalize_on_stack: false,
            act_on_hidden_el: false
        },
        init: function(scope, method, options) {
            Foundation.inherit(this, "image_loaded");
            this.bindings(method, options);
            this.reflow();
        },
        events: function() {
            this.S(window).off(".equalizer").on("resize.fndtn.equalizer", function(e) {
                this.reflow();
            }.bind(this));
        },
        equalize: function(equalizer) {
            var isStacked = false, group = equalizer.data("equalizer"), settings = equalizer.data(this.attr_name(true) + "-init") || this.settings, vals, firstTopOffset;
            if (settings.act_on_hidden_el) {
                vals = group ? equalizer.find("[" + this.attr_name() + '-watch="' + group + '"]') : equalizer.find("[" + this.attr_name() + "-watch]");
            } else {
                vals = group ? equalizer.find("[" + this.attr_name() + '-watch="' + group + '"]:visible') : equalizer.find("[" + this.attr_name() + "-watch]:visible");
            }
            if (vals.length === 0) {
                return;
            }
            settings.before_height_change();
            equalizer.trigger("before-height-change.fndth.equalizer");
            vals.height("inherit");
            if (settings.equalize_on_stack === false) {
                firstTopOffset = vals.first().offset().top;
                vals.each(function() {
                    if ($(this).offset().top !== firstTopOffset) {
                        isStacked = true;
                        return false;
                    }
                });
                if (isStacked) {
                    return;
                }
            }
            var heights = vals.map(function() {
                return $(this).outerHeight(false);
            }).get();
            if (settings.use_tallest) {
                var max = Math.max.apply(null, heights);
                vals.css("height", max);
            } else {
                var min = Math.min.apply(null, heights);
                vals.css("height", min);
            }
            settings.after_height_change();
            equalizer.trigger("after-height-change.fndtn.equalizer");
        },
        reflow: function() {
            var self = this;
            this.S("[" + this.attr_name() + "]", this.scope).each(function() {
                var $eq_target = $(this), media_query = $eq_target.data("equalizer-mq"), ignore_media_query = true;
                if (media_query) {
                    media_query = "is_" + media_query.replace(/-/g, "_");
                    if (Foundation.utils.hasOwnProperty(media_query)) {
                        ignore_media_query = false;
                    }
                }
                self.image_loaded(self.S("img", this), function() {
                    if (ignore_media_query || Foundation.utils[media_query]()) {
                        self.equalize($eq_target);
                    } else {
                        var vals = $eq_target.find("[" + self.attr_name() + "-watch]:visible");
                        vals.css("height", "auto");
                    }
                });
            });
        }
    };
})(jQuery, window, window.document);

(function($, window, document, undefined) {
    "use strict";
    Foundation.libs.interchange = {
        name: "interchange",
        version: "5.5.3",
        cache: {},
        images_loaded: false,
        nodes_loaded: false,
        settings: {
            load_attr: "interchange",
            named_queries: {
                "default": "only screen",
                small: Foundation.media_queries["small"],
                "small-only": Foundation.media_queries["small-only"],
                medium: Foundation.media_queries["medium"],
                "medium-only": Foundation.media_queries["medium-only"],
                large: Foundation.media_queries["large"],
                "large-only": Foundation.media_queries["large-only"],
                xlarge: Foundation.media_queries["xlarge"],
                "xlarge-only": Foundation.media_queries["xlarge-only"],
                xxlarge: Foundation.media_queries["xxlarge"],
                landscape: "only screen and (orientation: landscape)",
                portrait: "only screen and (orientation: portrait)",
                retina: "only screen and (-webkit-min-device-pixel-ratio: 2)," + "only screen and (min--moz-device-pixel-ratio: 2)," + "only screen and (-o-min-device-pixel-ratio: 2/1)," + "only screen and (min-device-pixel-ratio: 2)," + "only screen and (min-resolution: 192dpi)," + "only screen and (min-resolution: 2dppx)"
            },
            directives: {
                replace: function(el, path, trigger) {
                    if (el !== null && /IMG/.test(el[0].nodeName)) {
                        var orig_path = $.each(el, function() {
                            this.src = path;
                        });
                        if (new RegExp(path, "i").test(orig_path)) {
                            return;
                        }
                        el.attr("src", path);
                        return trigger(el[0].src);
                    }
                    var last_path = el.data(this.data_attr + "-last-path"), self = this;
                    if (last_path == path) {
                        return;
                    }
                    if (/\.(gif|jpg|jpeg|tiff|png)([?#].*)?/i.test(path)) {
                        $(el).css("background-image", "url(" + path + ")");
                        el.data("interchange-last-path", path);
                        return trigger(path);
                    }
                    return $.get(path, function(response) {
                        el.html(response);
                        el.data(self.data_attr + "-last-path", path);
                        trigger();
                    });
                }
            }
        },
        init: function(scope, method, options) {
            Foundation.inherit(this, "throttle random_str");
            this.data_attr = this.set_data_attr();
            $.extend(true, this.settings, method, options);
            this.bindings(method, options);
            this.reflow();
        },
        get_media_hash: function() {
            var mediaHash = "";
            for (var queryName in this.settings.named_queries) {
                mediaHash += matchMedia(this.settings.named_queries[queryName]).matches.toString();
            }
            return mediaHash;
        },
        events: function() {
            var self = this, prevMediaHash;
            $(window).off(".interchange").on("resize.fndtn.interchange", self.throttle(function() {
                var currMediaHash = self.get_media_hash();
                if (currMediaHash !== prevMediaHash) {
                    self.resize();
                }
                prevMediaHash = currMediaHash;
            }, 50));
            return this;
        },
        resize: function() {
            var cache = this.cache;
            if (!this.images_loaded || !this.nodes_loaded) {
                setTimeout($.proxy(this.resize, this), 50);
                return;
            }
            for (var uuid in cache) {
                if (cache.hasOwnProperty(uuid)) {
                    var passed = this.results(uuid, cache[uuid]);
                    if (passed) {
                        this.settings.directives[passed.scenario[1]].call(this, passed.el, passed.scenario[0], function(passed) {
                            if (arguments[0] instanceof Array) {
                                var args = arguments[0];
                            } else {
                                var args = Array.prototype.slice.call(arguments, 0);
                            }
                            return function() {
                                passed.el.trigger(passed.scenario[1], args);
                            };
                        }(passed));
                    }
                }
            }
        },
        results: function(uuid, scenarios) {
            var count = scenarios.length;
            if (count > 0) {
                var el = this.S("[" + this.add_namespace("data-uuid") + '="' + uuid + '"]');
                while (count--) {
                    var mq, rule = scenarios[count][2];
                    if (this.settings.named_queries.hasOwnProperty(rule)) {
                        mq = matchMedia(this.settings.named_queries[rule]);
                    } else {
                        mq = matchMedia(rule);
                    }
                    if (mq.matches) {
                        return {
                            el: el,
                            scenario: scenarios[count]
                        };
                    }
                }
            }
            return false;
        },
        load: function(type, force_update) {
            if (typeof this["cached_" + type] === "undefined" || force_update) {
                this["update_" + type]();
            }
            return this["cached_" + type];
        },
        update_images: function() {
            var images = this.S("img[" + this.data_attr + "]"), count = images.length, i = count, loaded_count = 0, data_attr = this.data_attr;
            this.cache = {};
            this.cached_images = [];
            this.images_loaded = count === 0;
            while (i--) {
                loaded_count++;
                if (images[i]) {
                    var str = images[i].getAttribute(data_attr) || "";
                    if (str.length > 0) {
                        this.cached_images.push(images[i]);
                    }
                }
                if (loaded_count === count) {
                    this.images_loaded = true;
                    this.enhance("images");
                }
            }
            return this;
        },
        update_nodes: function() {
            var nodes = this.S("[" + this.data_attr + "]").not("img"), count = nodes.length, i = count, loaded_count = 0, data_attr = this.data_attr;
            this.cached_nodes = [];
            this.nodes_loaded = count === 0;
            while (i--) {
                loaded_count++;
                var str = nodes[i].getAttribute(data_attr) || "";
                if (str.length > 0) {
                    this.cached_nodes.push(nodes[i]);
                }
                if (loaded_count === count) {
                    this.nodes_loaded = true;
                    this.enhance("nodes");
                }
            }
            return this;
        },
        enhance: function(type) {
            var i = this["cached_" + type].length;
            while (i--) {
                this.object($(this["cached_" + type][i]));
            }
            return $(window).trigger("resize.fndtn.interchange");
        },
        convert_directive: function(directive) {
            var trimmed = this.trim(directive);
            if (trimmed.length > 0) {
                return trimmed;
            }
            return "replace";
        },
        parse_scenario: function(scenario) {
            var directive_match = scenario[0].match(/(.+),\s*(\w+)\s*$/), media_query = scenario[1].match(/(.*)\)/);
            if (directive_match) {
                var path = directive_match[1], directive = directive_match[2];
            } else {
                var cached_split = scenario[0].split(/,\s*$/), path = cached_split[0], directive = "";
            }
            return [ this.trim(path), this.convert_directive(directive), this.trim(media_query[1]) ];
        },
        object: function(el) {
            var raw_arr = this.parse_data_attr(el), scenarios = [], i = raw_arr.length;
            if (i > 0) {
                while (i--) {
                    var scenario = raw_arr[i].split(/,\s?\(/);
                    if (scenario.length > 1) {
                        var params = this.parse_scenario(scenario);
                        scenarios.push(params);
                    }
                }
            }
            return this.store(el, scenarios);
        },
        store: function(el, scenarios) {
            var uuid = this.random_str(), current_uuid = el.data(this.add_namespace("uuid", true));
            if (this.cache[current_uuid]) {
                return this.cache[current_uuid];
            }
            el.attr(this.add_namespace("data-uuid"), uuid);
            return this.cache[uuid] = scenarios;
        },
        trim: function(str) {
            if (typeof str === "string") {
                return $.trim(str);
            }
            return str;
        },
        set_data_attr: function(init) {
            if (init) {
                if (this.namespace.length > 0) {
                    return this.namespace + "-" + this.settings.load_attr;
                }
                return this.settings.load_attr;
            }
            if (this.namespace.length > 0) {
                return "data-" + this.namespace + "-" + this.settings.load_attr;
            }
            return "data-" + this.settings.load_attr;
        },
        parse_data_attr: function(el) {
            var raw = el.attr(this.attr_name()).split(/\[(.*?)\]/), i = raw.length, output = [];
            while (i--) {
                if (raw[i].replace(/[\W\d]+/, "").length > 4) {
                    output.push(raw[i]);
                }
            }
            return output;
        },
        reflow: function() {
            this.load("images", true);
            this.load("nodes", true);
        }
    };
})(jQuery, window, window.document);

(function($, window, document, undefined) {
    "use strict";
    var Modernizr = Modernizr || false;
    Foundation.libs.joyride = {
        name: "joyride",
        version: "5.5.3",
        defaults: {
            expose: false,
            modal: true,
            keyboard: true,
            tip_location: "bottom",
            nub_position: "auto",
            scroll_speed: 1500,
            scroll_animation: "linear",
            timer: 0,
            start_timer_on_click: true,
            start_offset: 0,
            next_button: true,
            prev_button: true,
            tip_animation: "fade",
            pause_after: [],
            exposed: [],
            tip_animation_fade_speed: 300,
            cookie_monster: false,
            cookie_name: "joyride",
            cookie_domain: false,
            cookie_expires: 365,
            tip_container: "body",
            abort_on_close: true,
            tip_location_patterns: {
                top: [ "bottom" ],
                bottom: [],
                left: [ "right", "top", "bottom" ],
                right: [ "left", "top", "bottom" ]
            },
            post_ride_callback: function() {},
            post_step_callback: function() {},
            pre_step_callback: function() {},
            pre_ride_callback: function() {},
            post_expose_callback: function() {},
            template: {
                link: '<a href="#close" class="joyride-close-tip">&times;</a>',
                timer: '<div class="joyride-timer-indicator-wrap"><span class="joyride-timer-indicator"></span></div>',
                tip: '<div class="joyride-tip-guide"><span class="joyride-nub"></span></div>',
                wrapper: '<div class="joyride-content-wrapper"></div>',
                button: '<a href="#" class="small button joyride-next-tip"></a>',
                prev_button: '<a href="#" class="small button joyride-prev-tip"></a>',
                modal: '<div class="joyride-modal-bg"></div>',
                expose: '<div class="joyride-expose-wrapper"></div>',
                expose_cover: '<div class="joyride-expose-cover"></div>'
            },
            expose_add_class: ""
        },
        init: function(scope, method, options) {
            Foundation.inherit(this, "throttle random_str");
            this.settings = this.settings || $.extend({}, this.defaults, options || method);
            this.bindings(method, options);
        },
        go_next: function() {
            if (this.settings.$li.next().length < 1) {
                this.end();
            } else if (this.settings.timer > 0) {
                clearTimeout(this.settings.automate);
                this.hide();
                this.show();
                this.startTimer();
            } else {
                this.hide();
                this.show();
            }
        },
        go_prev: function() {
            if (this.settings.$li.prev().length < 1) {} else if (this.settings.timer > 0) {
                clearTimeout(this.settings.automate);
                this.hide();
                this.show(null, true);
                this.startTimer();
            } else {
                this.hide();
                this.show(null, true);
            }
        },
        events: function() {
            var self = this;
            $(this.scope).off(".joyride").on("click.fndtn.joyride", ".joyride-next-tip, .joyride-modal-bg", function(e) {
                e.preventDefault();
                this.go_next();
            }.bind(this)).on("click.fndtn.joyride", ".joyride-prev-tip", function(e) {
                e.preventDefault();
                this.go_prev();
            }.bind(this)).on("click.fndtn.joyride", ".joyride-close-tip", function(e) {
                e.preventDefault();
                this.end(this.settings.abort_on_close);
            }.bind(this)).on("keyup.fndtn.joyride", function(e) {
                if (!this.settings.keyboard || !this.settings.riding) {
                    return;
                }
                switch (e.which) {
                  case 39:
                    e.preventDefault();
                    this.go_next();
                    break;

                  case 37:
                    e.preventDefault();
                    this.go_prev();
                    break;

                  case 27:
                    e.preventDefault();
                    this.end(this.settings.abort_on_close);
                }
            }.bind(this));
            $(window).off(".joyride").on("resize.fndtn.joyride", self.throttle(function() {
                if ($("[" + self.attr_name() + "]").length > 0 && self.settings.$next_tip && self.settings.riding) {
                    if (self.settings.exposed.length > 0) {
                        var $els = $(self.settings.exposed);
                        $els.each(function() {
                            var $this = $(this);
                            self.un_expose($this);
                            self.expose($this);
                        });
                    }
                    if (self.is_phone()) {
                        self.pos_phone();
                    } else {
                        self.pos_default(false);
                    }
                }
            }, 100));
        },
        start: function() {
            var self = this, $this = $("[" + this.attr_name() + "]", this.scope), integer_settings = [ "timer", "scrollSpeed", "startOffset", "tipAnimationFadeSpeed", "cookieExpires" ], int_settings_count = integer_settings.length;
            if (!$this.length > 0) {
                return;
            }
            if (!this.settings.init) {
                this.events();
            }
            this.settings = $this.data(this.attr_name(true) + "-init");
            this.settings.$content_el = $this;
            this.settings.$body = $(this.settings.tip_container);
            this.settings.body_offset = $(this.settings.tip_container).position();
            this.settings.$tip_content = this.settings.$content_el.find("> li");
            this.settings.paused = false;
            this.settings.attempts = 0;
            this.settings.riding = true;
            if (typeof $.cookie !== "function") {
                this.settings.cookie_monster = false;
            }
            if (!this.settings.cookie_monster || this.settings.cookie_monster && !$.cookie(this.settings.cookie_name)) {
                this.settings.$tip_content.each(function(index) {
                    var $this = $(this);
                    this.settings = $.extend({}, self.defaults, self.data_options($this));
                    var i = int_settings_count;
                    while (i--) {
                        self.settings[integer_settings[i]] = parseInt(self.settings[integer_settings[i]], 10);
                    }
                    self.create({
                        $li: $this,
                        index: index
                    });
                });
                if (!this.settings.start_timer_on_click && this.settings.timer > 0) {
                    this.show("init");
                    this.startTimer();
                } else {
                    this.show("init");
                }
            }
        },
        resume: function() {
            this.set_li();
            this.show();
        },
        tip_template: function(opts) {
            var $blank, content;
            opts.tip_class = opts.tip_class || "";
            $blank = $(this.settings.template.tip).addClass(opts.tip_class);
            content = $.trim($(opts.li).html()) + this.prev_button_text(opts.prev_button_text, opts.index) + this.button_text(opts.button_text) + this.settings.template.link + this.timer_instance(opts.index);
            $blank.append($(this.settings.template.wrapper));
            $blank.first().attr(this.add_namespace("data-index"), opts.index);
            $(".joyride-content-wrapper", $blank).append(content);
            return $blank[0];
        },
        timer_instance: function(index) {
            var txt;
            if (index === 0 && this.settings.start_timer_on_click && this.settings.timer > 0 || this.settings.timer === 0) {
                txt = "";
            } else {
                txt = $(this.settings.template.timer)[0].outerHTML;
            }
            return txt;
        },
        button_text: function(txt) {
            if (this.settings.tip_settings.next_button) {
                txt = $.trim(txt) || "Next";
                txt = $(this.settings.template.button).append(txt)[0].outerHTML;
            } else {
                txt = "";
            }
            return txt;
        },
        prev_button_text: function(txt, idx) {
            if (this.settings.tip_settings.prev_button) {
                txt = $.trim(txt) || "Previous";
                if (idx == 0) {
                    txt = $(this.settings.template.prev_button).append(txt).addClass("disabled")[0].outerHTML;
                } else {
                    txt = $(this.settings.template.prev_button).append(txt)[0].outerHTML;
                }
            } else {
                txt = "";
            }
            return txt;
        },
        create: function(opts) {
            this.settings.tip_settings = $.extend({}, this.settings, this.data_options(opts.$li));
            var buttonText = opts.$li.attr(this.add_namespace("data-button")) || opts.$li.attr(this.add_namespace("data-text")), prevButtonText = opts.$li.attr(this.add_namespace("data-button-prev")) || opts.$li.attr(this.add_namespace("data-prev-text")), tipClass = opts.$li.attr("class"), $tip_content = $(this.tip_template({
                tip_class: tipClass,
                index: opts.index,
                button_text: buttonText,
                prev_button_text: prevButtonText,
                li: opts.$li
            }));
            $(this.settings.tip_container).append($tip_content);
        },
        show: function(init, is_prev) {
            var $timer = null;
            if (this.settings.$li === undefined || $.inArray(this.settings.$li.index(), this.settings.pause_after) === -1) {
                if (this.settings.paused) {
                    this.settings.paused = false;
                } else {
                    this.set_li(init, is_prev);
                }
                this.settings.attempts = 0;
                if (this.settings.$li.length && this.settings.$target.length > 0) {
                    if (init) {
                        this.settings.pre_ride_callback(this.settings.$li.index(), this.settings.$next_tip);
                        if (this.settings.modal) {
                            this.show_modal();
                        }
                    }
                    this.settings.pre_step_callback(this.settings.$li.index(), this.settings.$next_tip);
                    if (this.settings.modal && this.settings.expose) {
                        this.expose();
                    }
                    this.settings.tip_settings = $.extend({}, this.settings, this.data_options(this.settings.$li));
                    this.settings.timer = parseInt(this.settings.timer, 10);
                    this.settings.tip_settings.tip_location_pattern = this.settings.tip_location_patterns[this.settings.tip_settings.tip_location];
                    if (!/body/i.test(this.settings.$target.selector) && !this.settings.expose) {
                        var joyridemodalbg = $(".joyride-modal-bg");
                        if (/pop/i.test(this.settings.tipAnimation)) {
                            joyridemodalbg.hide();
                        } else {
                            joyridemodalbg.fadeOut(this.settings.tipAnimationFadeSpeed);
                        }
                        this.scroll_to();
                    }
                    if (this.is_phone()) {
                        this.pos_phone(true);
                    } else {
                        this.pos_default(true);
                    }
                    $timer = this.settings.$next_tip.find(".joyride-timer-indicator");
                    if (/pop/i.test(this.settings.tip_animation)) {
                        $timer.width(0);
                        if (this.settings.timer > 0) {
                            this.settings.$next_tip.show();
                            setTimeout(function() {
                                $timer.animate({
                                    width: $timer.parent().width()
                                }, this.settings.timer, "linear");
                            }.bind(this), this.settings.tip_animation_fade_speed);
                        } else {
                            this.settings.$next_tip.show();
                        }
                    } else if (/fade/i.test(this.settings.tip_animation)) {
                        $timer.width(0);
                        if (this.settings.timer > 0) {
                            this.settings.$next_tip.fadeIn(this.settings.tip_animation_fade_speed).show();
                            setTimeout(function() {
                                $timer.animate({
                                    width: $timer.parent().width()
                                }, this.settings.timer, "linear");
                            }.bind(this), this.settings.tip_animation_fade_speed);
                        } else {
                            this.settings.$next_tip.fadeIn(this.settings.tip_animation_fade_speed);
                        }
                    }
                    this.settings.$current_tip = this.settings.$next_tip;
                } else if (this.settings.$li && this.settings.$target.length < 1) {
                    this.show(init, is_prev);
                } else {
                    this.end();
                }
            } else {
                this.settings.paused = true;
            }
        },
        is_phone: function() {
            return matchMedia(Foundation.media_queries.small).matches && !matchMedia(Foundation.media_queries.medium).matches;
        },
        hide: function() {
            if (this.settings.modal && this.settings.expose) {
                this.un_expose();
            }
            if (!this.settings.modal) {
                $(".joyride-modal-bg").hide();
            }
            this.settings.$current_tip.css("visibility", "hidden");
            setTimeout($.proxy(function() {
                this.hide();
                this.css("visibility", "visible");
            }, this.settings.$current_tip), 0);
            this.settings.post_step_callback(this.settings.$li.index(), this.settings.$current_tip);
        },
        set_li: function(init, is_prev) {
            if (init) {
                this.settings.$li = this.settings.$tip_content.eq(this.settings.start_offset);
                this.set_next_tip();
                this.settings.$current_tip = this.settings.$next_tip;
            } else {
                if (is_prev) {
                    this.settings.$li = this.settings.$li.prev();
                } else {
                    this.settings.$li = this.settings.$li.next();
                }
                this.set_next_tip();
            }
            this.set_target();
        },
        set_next_tip: function() {
            this.settings.$next_tip = $(".joyride-tip-guide").eq(this.settings.$li.index());
            this.settings.$next_tip.data("closed", "");
        },
        set_target: function() {
            var cl = this.settings.$li.attr(this.add_namespace("data-class")), id = this.settings.$li.attr(this.add_namespace("data-id")), $sel = function() {
                if (id) {
                    return $(document.getElementById(id));
                } else if (cl) {
                    return $("." + cl).first();
                } else {
                    return $("body");
                }
            };
            this.settings.$target = $sel();
        },
        scroll_to: function() {
            var window_half, tipOffset;
            window_half = $(window).height() / 2;
            tipOffset = Math.ceil(this.settings.$target.offset().top - window_half + this.settings.$next_tip.outerHeight());
            if (tipOffset != 0) {
                $("html, body").stop().animate({
                    scrollTop: tipOffset
                }, this.settings.scroll_speed, "swing");
            }
        },
        paused: function() {
            return $.inArray(this.settings.$li.index() + 1, this.settings.pause_after) === -1;
        },
        restart: function() {
            this.hide();
            this.settings.$li = undefined;
            this.show("init");
        },
        pos_default: function(init) {
            var $nub = this.settings.$next_tip.find(".joyride-nub"), nub_width = Math.ceil($nub.outerWidth() / 2), nub_height = Math.ceil($nub.outerHeight() / 2), toggle = init || false;
            if (toggle) {
                this.settings.$next_tip.css("visibility", "hidden");
                this.settings.$next_tip.show();
            }
            if (!/body/i.test(this.settings.$target.selector)) {
                var topAdjustment = this.settings.tip_settings.tipAdjustmentY ? parseInt(this.settings.tip_settings.tipAdjustmentY) : 0, leftAdjustment = this.settings.tip_settings.tipAdjustmentX ? parseInt(this.settings.tip_settings.tipAdjustmentX) : 0;
                if (this.bottom()) {
                    if (this.rtl) {
                        this.settings.$next_tip.css({
                            top: this.settings.$target.offset().top + nub_height + this.settings.$target.outerHeight() + topAdjustment,
                            left: this.settings.$target.offset().left + this.settings.$target.outerWidth() - this.settings.$next_tip.outerWidth() + leftAdjustment
                        });
                    } else {
                        this.settings.$next_tip.css({
                            top: this.settings.$target.offset().top + nub_height + this.settings.$target.outerHeight() + topAdjustment,
                            left: this.settings.$target.offset().left + leftAdjustment
                        });
                    }
                    this.nub_position($nub, this.settings.tip_settings.nub_position, "top");
                } else if (this.top()) {
                    if (this.rtl) {
                        this.settings.$next_tip.css({
                            top: this.settings.$target.offset().top - this.settings.$next_tip.outerHeight() - nub_height + topAdjustment,
                            left: this.settings.$target.offset().left + this.settings.$target.outerWidth() - this.settings.$next_tip.outerWidth()
                        });
                    } else {
                        this.settings.$next_tip.css({
                            top: this.settings.$target.offset().top - this.settings.$next_tip.outerHeight() - nub_height + topAdjustment,
                            left: this.settings.$target.offset().left + leftAdjustment
                        });
                    }
                    this.nub_position($nub, this.settings.tip_settings.nub_position, "bottom");
                } else if (this.right()) {
                    this.settings.$next_tip.css({
                        top: this.settings.$target.offset().top + topAdjustment,
                        left: this.settings.$target.outerWidth() + this.settings.$target.offset().left + nub_width + leftAdjustment
                    });
                    this.nub_position($nub, this.settings.tip_settings.nub_position, "left");
                } else if (this.left()) {
                    this.settings.$next_tip.css({
                        top: this.settings.$target.offset().top + topAdjustment,
                        left: this.settings.$target.offset().left - this.settings.$next_tip.outerWidth() - nub_width + leftAdjustment
                    });
                    this.nub_position($nub, this.settings.tip_settings.nub_position, "right");
                }
                if (!this.visible(this.corners(this.settings.$next_tip)) && this.settings.attempts < this.settings.tip_settings.tip_location_pattern.length) {
                    $nub.removeClass("bottom").removeClass("top").removeClass("right").removeClass("left");
                    this.settings.tip_settings.tip_location = this.settings.tip_settings.tip_location_pattern[this.settings.attempts];
                    this.settings.attempts++;
                    this.pos_default();
                }
            } else if (this.settings.$li.length) {
                this.pos_modal($nub);
            }
            if (toggle) {
                this.settings.$next_tip.hide();
                this.settings.$next_tip.css("visibility", "visible");
            }
        },
        pos_phone: function(init) {
            var tip_height = this.settings.$next_tip.outerHeight(), tip_offset = this.settings.$next_tip.offset(), target_height = this.settings.$target.outerHeight(), $nub = $(".joyride-nub", this.settings.$next_tip), nub_height = Math.ceil($nub.outerHeight() / 2), toggle = init || false;
            $nub.removeClass("bottom").removeClass("top").removeClass("right").removeClass("left");
            if (toggle) {
                this.settings.$next_tip.css("visibility", "hidden");
                this.settings.$next_tip.show();
            }
            if (!/body/i.test(this.settings.$target.selector)) {
                if (this.top()) {
                    this.settings.$next_tip.offset({
                        top: this.settings.$target.offset().top - tip_height - nub_height
                    });
                    $nub.addClass("bottom");
                } else {
                    this.settings.$next_tip.offset({
                        top: this.settings.$target.offset().top + target_height + nub_height
                    });
                    $nub.addClass("top");
                }
            } else if (this.settings.$li.length) {
                this.pos_modal($nub);
            }
            if (toggle) {
                this.settings.$next_tip.hide();
                this.settings.$next_tip.css("visibility", "visible");
            }
        },
        pos_modal: function($nub) {
            this.center();
            $nub.hide();
            this.show_modal();
        },
        show_modal: function() {
            if (!this.settings.$next_tip.data("closed")) {
                var joyridemodalbg = $(".joyride-modal-bg");
                if (joyridemodalbg.length < 1) {
                    var joyridemodalbg = $(this.settings.template.modal);
                    joyridemodalbg.appendTo("body");
                }
                if (/pop/i.test(this.settings.tip_animation)) {
                    joyridemodalbg.show();
                } else {
                    joyridemodalbg.fadeIn(this.settings.tip_animation_fade_speed);
                }
            }
        },
        expose: function() {
            var expose, exposeCover, el, origCSS, origClasses, randId = "expose-" + this.random_str(6);
            if (arguments.length > 0 && arguments[0] instanceof $) {
                el = arguments[0];
            } else if (this.settings.$target && !/body/i.test(this.settings.$target.selector)) {
                el = this.settings.$target;
            } else {
                return false;
            }
            if (el.length < 1) {
                if (window.console) {
                    console.error("element not valid", el);
                }
                return false;
            }
            expose = $(this.settings.template.expose);
            this.settings.$body.append(expose);
            expose.css({
                top: el.offset().top,
                left: el.offset().left,
                width: el.outerWidth(true),
                height: el.outerHeight(true)
            });
            exposeCover = $(this.settings.template.expose_cover);
            origCSS = {
                zIndex: el.css("z-index"),
                position: el.css("position")
            };
            origClasses = el.attr("class") == null ? "" : el.attr("class");
            el.css("z-index", parseInt(expose.css("z-index")) + 1);
            if (origCSS.position == "static") {
                el.css("position", "relative");
            }
            el.data("expose-css", origCSS);
            el.data("orig-class", origClasses);
            el.attr("class", origClasses + " " + this.settings.expose_add_class);
            exposeCover.css({
                top: el.offset().top,
                left: el.offset().left,
                width: el.outerWidth(true),
                height: el.outerHeight(true)
            });
            if (this.settings.modal) {
                this.show_modal();
            }
            this.settings.$body.append(exposeCover);
            expose.addClass(randId);
            exposeCover.addClass(randId);
            el.data("expose", randId);
            this.settings.post_expose_callback(this.settings.$li.index(), this.settings.$next_tip, el);
            this.add_exposed(el);
        },
        un_expose: function() {
            var exposeId, el, expose, origCSS, origClasses, clearAll = false;
            if (arguments.length > 0 && arguments[0] instanceof $) {
                el = arguments[0];
            } else if (this.settings.$target && !/body/i.test(this.settings.$target.selector)) {
                el = this.settings.$target;
            } else {
                return false;
            }
            if (el.length < 1) {
                if (window.console) {
                    console.error("element not valid", el);
                }
                return false;
            }
            exposeId = el.data("expose");
            expose = $("." + exposeId);
            if (arguments.length > 1) {
                clearAll = arguments[1];
            }
            if (clearAll === true) {
                $(".joyride-expose-wrapper,.joyride-expose-cover").remove();
            } else {
                expose.remove();
            }
            origCSS = el.data("expose-css");
            if (origCSS.zIndex == "auto") {
                el.css("z-index", "");
            } else {
                el.css("z-index", origCSS.zIndex);
            }
            if (origCSS.position != el.css("position")) {
                if (origCSS.position == "static") {
                    el.css("position", "");
                } else {
                    el.css("position", origCSS.position);
                }
            }
            origClasses = el.data("orig-class");
            el.attr("class", origClasses);
            el.removeData("orig-classes");
            el.removeData("expose");
            el.removeData("expose-z-index");
            this.remove_exposed(el);
        },
        add_exposed: function(el) {
            this.settings.exposed = this.settings.exposed || [];
            if (el instanceof $ || typeof el === "object") {
                this.settings.exposed.push(el[0]);
            } else if (typeof el == "string") {
                this.settings.exposed.push(el);
            }
        },
        remove_exposed: function(el) {
            var search, i;
            if (el instanceof $) {
                search = el[0];
            } else if (typeof el == "string") {
                search = el;
            }
            this.settings.exposed = this.settings.exposed || [];
            i = this.settings.exposed.length;
            while (i--) {
                if (this.settings.exposed[i] == search) {
                    this.settings.exposed.splice(i, 1);
                    return;
                }
            }
        },
        center: function() {
            var $w = $(window);
            this.settings.$next_tip.css({
                top: ($w.height() - this.settings.$next_tip.outerHeight()) / 2 + $w.scrollTop(),
                left: ($w.width() - this.settings.$next_tip.outerWidth()) / 2 + $w.scrollLeft()
            });
            return true;
        },
        bottom: function() {
            return /bottom/i.test(this.settings.tip_settings.tip_location);
        },
        top: function() {
            return /top/i.test(this.settings.tip_settings.tip_location);
        },
        right: function() {
            return /right/i.test(this.settings.tip_settings.tip_location);
        },
        left: function() {
            return /left/i.test(this.settings.tip_settings.tip_location);
        },
        corners: function(el) {
            if (el.length === 0) {
                return [ false, false, false, false ];
            }
            var w = $(window), window_half = w.height() / 2, tipOffset = Math.ceil(this.settings.$target.offset().top - window_half + this.settings.$next_tip.outerHeight()), right = w.width() + w.scrollLeft(), offsetBottom = w.height() + tipOffset, bottom = w.height() + w.scrollTop(), top = w.scrollTop();
            if (tipOffset < top) {
                if (tipOffset < 0) {
                    top = 0;
                } else {
                    top = tipOffset;
                }
            }
            if (offsetBottom > bottom) {
                bottom = offsetBottom;
            }
            return [ el.offset().top < top, right < el.offset().left + el.outerWidth(), bottom < el.offset().top + el.outerHeight(), w.scrollLeft() > el.offset().left ];
        },
        visible: function(hidden_corners) {
            var i = hidden_corners.length;
            while (i--) {
                if (hidden_corners[i]) {
                    return false;
                }
            }
            return true;
        },
        nub_position: function(nub, pos, def) {
            if (pos === "auto") {
                nub.addClass(def);
            } else {
                nub.addClass(pos);
            }
        },
        startTimer: function() {
            if (this.settings.$li.length) {
                this.settings.automate = setTimeout(function() {
                    this.hide();
                    this.show();
                    this.startTimer();
                }.bind(this), this.settings.timer);
            } else {
                clearTimeout(this.settings.automate);
            }
        },
        end: function(abort) {
            if (this.settings.cookie_monster) {
                $.cookie(this.settings.cookie_name, "ridden", {
                    expires: this.settings.cookie_expires,
                    domain: this.settings.cookie_domain
                });
            }
            if (this.settings.timer > 0) {
                clearTimeout(this.settings.automate);
            }
            if (this.settings.modal && this.settings.expose) {
                this.un_expose();
            }
            $(this.scope).off("keyup.joyride");
            this.settings.$next_tip.data("closed", true);
            this.settings.riding = false;
            $(".joyride-modal-bg").hide();
            this.settings.$current_tip.hide();
            if (typeof abort === "undefined" || abort === false) {
                this.settings.post_step_callback(this.settings.$li.index(), this.settings.$current_tip);
                this.settings.post_ride_callback(this.settings.$li.index(), this.settings.$current_tip);
            }
            $(".joyride-tip-guide").remove();
        },
        off: function() {
            $(this.scope).off(".joyride");
            $(window).off(".joyride");
            $(".joyride-close-tip, .joyride-next-tip, .joyride-modal-bg").off(".joyride");
            $(".joyride-tip-guide, .joyride-modal-bg").remove();
            clearTimeout(this.settings.automate);
        },
        reflow: function() {}
    };
})(jQuery, window, window.document);

(function($, window, document, undefined) {
    "use strict";
    Foundation.libs["magellan-expedition"] = {
        name: "magellan-expedition",
        version: "5.5.3",
        settings: {
            active_class: "active",
            threshold: 0,
            destination_threshold: 20,
            throttle_delay: 30,
            fixed_top: 0,
            offset_by_height: true,
            duration: 700,
            easing: "swing"
        },
        init: function(scope, method, options) {
            Foundation.inherit(this, "throttle");
            this.bindings(method, options);
        },
        events: function() {
            var self = this, S = self.S, settings = self.settings;
            self.set_expedition_position();
            S(self.scope).off(".magellan").on("click.fndtn.magellan", "[" + self.add_namespace("data-magellan-arrival") + "] a[href*=#]", function(e) {
                var sameHost = this.hostname === location.hostname || !this.hostname, samePath = self.filterPathname(location.pathname) === self.filterPathname(this.pathname), testHash = this.hash.replace(/(:|\.|\/)/g, "\\$1"), anchor = this;
                if (sameHost && samePath && testHash) {
                    e.preventDefault();
                    var expedition = $(this).closest("[" + self.attr_name() + "]"), settings = expedition.data("magellan-expedition-init"), hash = this.hash.split("#").join(""), target = $('a[name="' + hash + '"]');
                    if (target.length === 0) {
                        target = $("#" + hash);
                    }
                    var scroll_top = target.offset().top - settings.destination_threshold + 1;
                    if (settings.offset_by_height) {
                        scroll_top = scroll_top - expedition.outerHeight();
                    }
                    $("html, body").stop().animate({
                        scrollTop: scroll_top
                    }, settings.duration, settings.easing, function() {
                        if (history.pushState) {
                            history.pushState(null, null, anchor.pathname + anchor.search + "#" + hash);
                        } else {
                            location.hash = anchor.pathname + anchor.search + "#" + hash;
                        }
                    });
                }
            }).on("scroll.fndtn.magellan", self.throttle(this.check_for_arrivals.bind(this), settings.throttle_delay));
        },
        check_for_arrivals: function() {
            var self = this;
            self.update_arrivals();
            self.update_expedition_positions();
        },
        set_expedition_position: function() {
            var self = this;
            $("[" + this.attr_name() + "=fixed]", self.scope).each(function(idx, el) {
                var expedition = $(this), settings = expedition.data("magellan-expedition-init"), styles = expedition.attr("styles"), top_offset, fixed_top;
                expedition.attr("style", "");
                top_offset = expedition.offset().top + settings.threshold;
                fixed_top = parseInt(expedition.data("magellan-fixed-top"));
                if (!isNaN(fixed_top)) {
                    self.settings.fixed_top = fixed_top;
                }
                expedition.data(self.data_attr("magellan-top-offset"), top_offset);
                expedition.attr("style", styles);
            });
        },
        update_expedition_positions: function() {
            var self = this, window_top_offset = $(window).scrollTop();
            $("[" + this.attr_name() + "=fixed]", self.scope).each(function() {
                var expedition = $(this), settings = expedition.data("magellan-expedition-init"), styles = expedition.attr("style"), top_offset = expedition.data("magellan-top-offset");
                if (window_top_offset + self.settings.fixed_top >= top_offset) {
                    var placeholder = expedition.prev("[" + self.add_namespace("data-magellan-expedition-clone") + "]");
                    if (placeholder.length === 0) {
                        placeholder = expedition.clone();
                        placeholder.removeAttr(self.attr_name());
                        placeholder.attr(self.add_namespace("data-magellan-expedition-clone"), "");
                        expedition.before(placeholder);
                    }
                    expedition.css({
                        position: "fixed",
                        top: settings.fixed_top
                    }).addClass("fixed");
                } else {
                    expedition.prev("[" + self.add_namespace("data-magellan-expedition-clone") + "]").remove();
                    expedition.attr("style", styles).css("position", "").css("top", "").removeClass("fixed");
                }
            });
        },
        update_arrivals: function() {
            var self = this, window_top_offset = $(window).scrollTop();
            $("[" + this.attr_name() + "]", self.scope).each(function() {
                var expedition = $(this), settings = expedition.data(self.attr_name(true) + "-init"), offsets = self.offsets(expedition, window_top_offset), arrivals = expedition.find("[" + self.add_namespace("data-magellan-arrival") + "]"), active_item = false;
                offsets.each(function(idx, item) {
                    if (item.viewport_offset >= item.top_offset) {
                        var arrivals = expedition.find("[" + self.add_namespace("data-magellan-arrival") + "]");
                        arrivals.not(item.arrival).removeClass(settings.active_class);
                        item.arrival.addClass(settings.active_class);
                        active_item = true;
                        return true;
                    }
                });
                if (!active_item) {
                    arrivals.removeClass(settings.active_class);
                }
            });
        },
        offsets: function(expedition, window_offset) {
            var self = this, settings = expedition.data(self.attr_name(true) + "-init"), viewport_offset = window_offset;
            return expedition.find("[" + self.add_namespace("data-magellan-arrival") + "]").map(function(idx, el) {
                var name = $(this).data(self.data_attr("magellan-arrival")), dest = $("[" + self.add_namespace("data-magellan-destination") + "=" + name + "]");
                if (dest.length > 0) {
                    var top_offset = dest.offset().top - settings.destination_threshold;
                    if (settings.offset_by_height) {
                        top_offset = top_offset - expedition.outerHeight();
                    }
                    top_offset = Math.floor(top_offset);
                    return {
                        destination: dest,
                        arrival: $(this),
                        top_offset: top_offset,
                        viewport_offset: viewport_offset
                    };
                }
            }).sort(function(a, b) {
                if (a.top_offset < b.top_offset) {
                    return -1;
                }
                if (a.top_offset > b.top_offset) {
                    return 1;
                }
                return 0;
            });
        },
        data_attr: function(str) {
            if (this.namespace.length > 0) {
                return this.namespace + "-" + str;
            }
            return str;
        },
        off: function() {
            this.S(this.scope).off(".magellan");
            this.S(window).off(".magellan");
        },
        filterPathname: function(pathname) {
            pathname = pathname || "";
            return pathname.replace(/^\//, "").replace(/(?:index|default).[a-zA-Z]{3,4}$/, "").replace(/\/$/, "");
        },
        reflow: function() {
            var self = this;
            $("[" + self.add_namespace("data-magellan-expedition-clone") + "]", self.scope).remove();
        }
    };
})(jQuery, window, window.document);

(function($, window, document, undefined) {
    "use strict";
    Foundation.libs.offcanvas = {
        name: "offcanvas",
        version: "5.5.3",
        settings: {
            open_method: "move",
            close_on_click: false
        },
        init: function(scope, method, options) {
            this.bindings(method, options);
        },
        events: function() {
            var self = this, S = self.S, move_class = "", right_postfix = "", left_postfix = "", top_postfix = "", bottom_postfix = "";
            if (this.settings.open_method === "move") {
                move_class = "move-";
                right_postfix = "right";
                left_postfix = "left";
                top_postfix = "top";
                bottom_postfix = "bottom";
            } else if (this.settings.open_method === "overlap_single") {
                move_class = "offcanvas-overlap-";
                right_postfix = "right";
                left_postfix = "left";
                top_postfix = "top";
                bottom_postfix = "bottom";
            } else if (this.settings.open_method === "overlap") {
                move_class = "offcanvas-overlap";
            }
            S(this.scope).off(".offcanvas").on("click.fndtn.offcanvas", ".left-off-canvas-toggle", function(e) {
                self.click_toggle_class(e, move_class + right_postfix);
                if (self.settings.open_method !== "overlap") {
                    S(".left-submenu").removeClass(move_class + right_postfix);
                }
                $(".left-off-canvas-toggle").attr("aria-expanded", "true");
            }).on("click.fndtn.offcanvas", ".left-off-canvas-menu a", function(e) {
                var settings = self.get_settings(e);
                var parent = S(this).parent();
                if (settings.close_on_click && !parent.hasClass("has-submenu") && !parent.hasClass("back")) {
                    self.hide.call(self, move_class + right_postfix, self.get_wrapper(e));
                    parent.parent().removeClass(move_class + right_postfix);
                } else if (S(this).parent().hasClass("has-submenu")) {
                    e.preventDefault();
                    S(this).siblings(".left-submenu").toggleClass(move_class + right_postfix);
                } else if (parent.hasClass("back")) {
                    e.preventDefault();
                    parent.parent().removeClass(move_class + right_postfix);
                }
                $(".left-off-canvas-toggle").attr("aria-expanded", "true");
            }).on("click.fndtn.offcanvas", ".right-off-canvas-toggle", function(e) {
                self.click_toggle_class(e, move_class + left_postfix);
                if (self.settings.open_method !== "overlap") {
                    S(".right-submenu").removeClass(move_class + left_postfix);
                }
                $(".right-off-canvas-toggle").attr("aria-expanded", "true");
            }).on("click.fndtn.offcanvas", ".right-off-canvas-menu a", function(e) {
                var settings = self.get_settings(e);
                var parent = S(this).parent();
                if (settings.close_on_click && !parent.hasClass("has-submenu") && !parent.hasClass("back")) {
                    self.hide.call(self, move_class + left_postfix, self.get_wrapper(e));
                    parent.parent().removeClass(move_class + left_postfix);
                } else if (S(this).parent().hasClass("has-submenu")) {
                    e.preventDefault();
                    S(this).siblings(".right-submenu").toggleClass(move_class + left_postfix);
                } else if (parent.hasClass("back")) {
                    e.preventDefault();
                    parent.parent().removeClass(move_class + left_postfix);
                }
                $(".right-off-canvas-toggle").attr("aria-expanded", "true");
            }).on("click.fndtn.offcanvas", ".top-off-canvas-toggle", function(e) {
                self.click_toggle_class(e, move_class + bottom_postfix);
                if (self.settings.open_method !== "overlap") {
                    S(".top-submenu").removeClass(move_class + bottom_postfix);
                }
                $(".top-off-canvas-toggle").attr("aria-expanded", "true");
            }).on("click.fndtn.offcanvas", ".top-off-canvas-menu a", function(e) {
                var settings = self.get_settings(e);
                var parent = S(this).parent();
                if (settings.close_on_click && !parent.hasClass("has-submenu") && !parent.hasClass("back")) {
                    self.hide.call(self, move_class + bottom_postfix, self.get_wrapper(e));
                    parent.parent().removeClass(move_class + bottom_postfix);
                } else if (S(this).parent().hasClass("has-submenu")) {
                    e.preventDefault();
                    S(this).siblings(".top-submenu").toggleClass(move_class + bottom_postfix);
                } else if (parent.hasClass("back")) {
                    e.preventDefault();
                    parent.parent().removeClass(move_class + bottom_postfix);
                }
                $(".top-off-canvas-toggle").attr("aria-expanded", "true");
            }).on("click.fndtn.offcanvas", ".bottom-off-canvas-toggle", function(e) {
                self.click_toggle_class(e, move_class + top_postfix);
                if (self.settings.open_method !== "overlap") {
                    S(".bottom-submenu").removeClass(move_class + top_postfix);
                }
                $(".bottom-off-canvas-toggle").attr("aria-expanded", "true");
            }).on("click.fndtn.offcanvas", ".bottom-off-canvas-menu a", function(e) {
                var settings = self.get_settings(e);
                var parent = S(this).parent();
                if (settings.close_on_click && !parent.hasClass("has-submenu") && !parent.hasClass("back")) {
                    self.hide.call(self, move_class + top_postfix, self.get_wrapper(e));
                    parent.parent().removeClass(move_class + top_postfix);
                } else if (S(this).parent().hasClass("has-submenu")) {
                    e.preventDefault();
                    S(this).siblings(".bottom-submenu").toggleClass(move_class + top_postfix);
                } else if (parent.hasClass("back")) {
                    e.preventDefault();
                    parent.parent().removeClass(move_class + top_postfix);
                }
                $(".bottom-off-canvas-toggle").attr("aria-expanded", "true");
            }).on("click.fndtn.offcanvas", ".exit-off-canvas", function(e) {
                self.click_remove_class(e, move_class + left_postfix);
                S(".right-submenu").removeClass(move_class + left_postfix);
                if (right_postfix) {
                    self.click_remove_class(e, move_class + right_postfix);
                    S(".left-submenu").removeClass(move_class + left_postfix);
                }
                $(".right-off-canvas-toggle").attr("aria-expanded", "true");
            }).on("click.fndtn.offcanvas", ".exit-off-canvas", function(e) {
                self.click_remove_class(e, move_class + left_postfix);
                $(".left-off-canvas-toggle").attr("aria-expanded", "false");
                if (right_postfix) {
                    self.click_remove_class(e, move_class + right_postfix);
                    $(".right-off-canvas-toggle").attr("aria-expanded", "false");
                }
            }).on("click.fndtn.offcanvas", ".exit-off-canvas", function(e) {
                self.click_remove_class(e, move_class + top_postfix);
                S(".bottom-submenu").removeClass(move_class + top_postfix);
                if (bottom_postfix) {
                    self.click_remove_class(e, move_class + bottom_postfix);
                    S(".top-submenu").removeClass(move_class + top_postfix);
                }
                $(".bottom-off-canvas-toggle").attr("aria-expanded", "true");
            }).on("click.fndtn.offcanvas", ".exit-off-canvas", function(e) {
                self.click_remove_class(e, move_class + top_postfix);
                $(".top-off-canvas-toggle").attr("aria-expanded", "false");
                if (bottom_postfix) {
                    self.click_remove_class(e, move_class + bottom_postfix);
                    $(".bottom-off-canvas-toggle").attr("aria-expanded", "false");
                }
            });
        },
        toggle: function(class_name, $off_canvas) {
            $off_canvas = $off_canvas || this.get_wrapper();
            if ($off_canvas.is("." + class_name)) {
                this.hide(class_name, $off_canvas);
            } else {
                this.show(class_name, $off_canvas);
            }
        },
        show: function(class_name, $off_canvas) {
            $off_canvas = $off_canvas || this.get_wrapper();
            $off_canvas.trigger("open.fndtn.offcanvas");
            $off_canvas.addClass(class_name);
        },
        hide: function(class_name, $off_canvas) {
            $off_canvas = $off_canvas || this.get_wrapper();
            $off_canvas.trigger("close.fndtn.offcanvas");
            $off_canvas.removeClass(class_name);
        },
        click_toggle_class: function(e, class_name) {
            e.preventDefault();
            var $off_canvas = this.get_wrapper(e);
            this.toggle(class_name, $off_canvas);
        },
        click_remove_class: function(e, class_name) {
            e.preventDefault();
            var $off_canvas = this.get_wrapper(e);
            this.hide(class_name, $off_canvas);
        },
        get_settings: function(e) {
            var offcanvas = this.S(e.target).closest("[" + this.attr_name() + "]");
            return offcanvas.data(this.attr_name(true) + "-init") || this.settings;
        },
        get_wrapper: function(e) {
            var $off_canvas = this.S(e ? e.target : this.scope).closest(".off-canvas-wrap");
            if ($off_canvas.length === 0) {
                $off_canvas = this.S(".off-canvas-wrap");
            }
            return $off_canvas;
        },
        reflow: function() {}
    };
})(jQuery, window, window.document);

(function($, window, document, undefined) {
    "use strict";
    var noop = function() {};
    var Orbit = function(el, settings) {
        if (el.hasClass(settings.slides_container_class)) {
            return this;
        }
        var self = this, container, slides_container = el, number_container, bullets_container, timer_container, idx = 0, animate, timer, locked = false, adjust_height_after = false;
        self.slides = function() {
            return slides_container.children(settings.slide_selector);
        };
        self.slides().first().addClass(settings.active_slide_class);
        self.update_slide_number = function(index) {
            if (settings.slide_number) {
                number_container.find("span:first").text(parseInt(index) + 1);
                number_container.find("span:last").text(self.slides().length);
            }
            if (settings.bullets) {
                bullets_container.children().removeClass(settings.bullets_active_class);
                $(bullets_container.children().get(index)).addClass(settings.bullets_active_class);
            }
        };
        self.update_active_link = function(index) {
            var link = $('[data-orbit-link="' + self.slides().eq(index).attr("data-orbit-slide") + '"]');
            link.siblings().removeClass(settings.bullets_active_class);
            link.addClass(settings.bullets_active_class);
        };
        self.build_markup = function() {
            slides_container.wrap('<div class="' + settings.container_class + '"></div>');
            container = slides_container.parent();
            slides_container.addClass(settings.slides_container_class);
            if (settings.stack_on_small) {
                container.addClass(settings.stack_on_small_class);
            }
            if (settings.navigation_arrows) {
                container.append($('<a href="#"><span></span></a>').addClass(settings.prev_class));
                container.append($('<a href="#"><span></span></a>').addClass(settings.next_class));
            }
            if (settings.timer) {
                timer_container = $("<div>").addClass(settings.timer_container_class);
                timer_container.append("<span>");
                timer_container.append($("<div>").addClass(settings.timer_progress_class));
                timer_container.addClass(settings.timer_paused_class);
                container.append(timer_container);
            }
            if (settings.slide_number) {
                number_container = $("<div>").addClass(settings.slide_number_class);
                number_container.append("<span></span> " + settings.slide_number_text + " <span></span>");
                container.append(number_container);
            }
            if (settings.bullets) {
                bullets_container = $("<ol>").addClass(settings.bullets_container_class);
                container.append(bullets_container);
                bullets_container.wrap('<div class="orbit-bullets-container"></div>');
                self.slides().each(function(idx, el) {
                    var bullet = $("<li>").attr("data-orbit-slide", idx).on("click", self.link_bullet);
                    bullets_container.append(bullet);
                });
            }
        };
        self._goto = function(next_idx, start_timer) {
            if (next_idx === idx) {
                return false;
            }
            if (typeof timer === "object") {
                timer.restart();
            }
            var slides = self.slides();
            var dir = "next";
            locked = true;
            if (next_idx < idx) {
                dir = "prev";
            }
            if (next_idx >= slides.length) {
                if (!settings.circular) {
                    return false;
                }
                next_idx = 0;
            } else if (next_idx < 0) {
                if (!settings.circular) {
                    return false;
                }
                next_idx = slides.length - 1;
            }
            var current = $(slides.get(idx));
            var next = $(slides.get(next_idx));
            current.css("zIndex", 2);
            current.removeClass(settings.active_slide_class);
            next.css("zIndex", 4).addClass(settings.active_slide_class);
            slides_container.trigger("before-slide-change.fndtn.orbit");
            settings.before_slide_change();
            self.update_active_link(next_idx);
            var callback = function() {
                var unlock = function() {
                    idx = next_idx;
                    locked = false;
                    if (start_timer === true) {
                        timer = self.create_timer();
                        timer.start();
                    }
                    self.update_slide_number(idx);
                    slides_container.trigger("after-slide-change.fndtn.orbit", [ {
                        slide_number: idx,
                        total_slides: slides.length
                    } ]);
                    settings.after_slide_change(idx, slides.length);
                };
                if (slides_container.outerHeight() != next.outerHeight() && settings.variable_height) {
                    slides_container.animate({
                        height: next.outerHeight()
                    }, 250, "linear", unlock);
                } else {
                    unlock();
                }
            };
            if (slides.length === 1) {
                callback();
                return false;
            }
            var start_animation = function() {
                if (dir === "next") {
                    animate.next(current, next, callback);
                }
                if (dir === "prev") {
                    animate.prev(current, next, callback);
                }
            };
            if (next.outerHeight() > slides_container.outerHeight() && settings.variable_height) {
                slides_container.animate({
                    height: next.outerHeight()
                }, 250, "linear", start_animation);
            } else {
                start_animation();
            }
        };
        self.next = function(e) {
            e.stopImmediatePropagation();
            e.preventDefault();
            self._goto(idx + 1);
        };
        self.prev = function(e) {
            e.stopImmediatePropagation();
            e.preventDefault();
            self._goto(idx - 1);
        };
        self.link_custom = function(e) {
            e.preventDefault();
            var link = $(this).attr("data-orbit-link");
            if (typeof link === "string" && (link = $.trim(link)) != "") {
                var slide = container.find("[data-orbit-slide=" + link + "]");
                if (slide.index() != -1) {
                    self._goto(slide.index());
                }
            }
        };
        self.link_bullet = function(e) {
            var index = $(this).attr("data-orbit-slide");
            if (typeof index === "string" && (index = $.trim(index)) != "") {
                if (isNaN(parseInt(index))) {
                    var slide = container.find("[data-orbit-slide=" + index + "]");
                    if (slide.index() != -1) {
                        self._goto(slide.index() + 1);
                    }
                } else {
                    self._goto(parseInt(index));
                }
            }
        };
        self.timer_callback = function() {
            self._goto(idx + 1, true);
        };
        self.compute_dimensions = function() {
            var current = $(self.slides().get(idx));
            var h = current.outerHeight();
            if (!settings.variable_height) {
                self.slides().each(function() {
                    if ($(this).outerHeight() > h) {
                        h = $(this).outerHeight();
                    }
                });
            }
            slides_container.height(h);
        };
        self.create_timer = function() {
            var t = new Timer(container.find("." + settings.timer_container_class), settings, self.timer_callback);
            return t;
        };
        self.stop_timer = function() {
            if (typeof timer === "object") {
                timer.stop();
            }
        };
        self.toggle_timer = function() {
            var t = container.find("." + settings.timer_container_class);
            if (t.hasClass(settings.timer_paused_class)) {
                if (typeof timer === "undefined") {
                    timer = self.create_timer();
                }
                timer.start();
            } else {
                if (typeof timer === "object") {
                    timer.stop();
                }
            }
        };
        self.init = function() {
            self.build_markup();
            if (settings.timer) {
                timer = self.create_timer();
                Foundation.utils.image_loaded(this.slides().children("img"), timer.start);
            }
            animate = new FadeAnimation(settings, slides_container);
            if (settings.animation === "slide") {
                animate = new SlideAnimation(settings, slides_container);
            }
            container.on("click", "." + settings.next_class, self.next);
            container.on("click", "." + settings.prev_class, self.prev);
            if (settings.next_on_click) {
                container.on("click", "." + settings.slides_container_class + " [data-orbit-slide]", self.link_bullet);
            }
            container.on("click", self.toggle_timer);
            if (settings.swipe) {
                container.on("touchstart.fndtn.orbit", function(e) {
                    if (!e.touches) {
                        e = e.originalEvent;
                    }
                    var data = {
                        start_page_x: e.touches[0].pageX,
                        start_page_y: e.touches[0].pageY,
                        start_time: new Date().getTime(),
                        delta_x: 0,
                        is_scrolling: undefined
                    };
                    container.data("swipe-transition", data);
                    e.stopPropagation();
                }).on("touchmove.fndtn.orbit", function(e) {
                    if (!e.touches) {
                        e = e.originalEvent;
                    }
                    if (e.touches.length > 1 || e.scale && e.scale !== 1) {
                        return;
                    }
                    var data = container.data("swipe-transition");
                    if (typeof data === "undefined") {
                        data = {};
                    }
                    data.delta_x = e.touches[0].pageX - data.start_page_x;
                    if (typeof data.is_scrolling === "undefined") {
                        data.is_scrolling = !!(data.is_scrolling || Math.abs(data.delta_x) < Math.abs(e.touches[0].pageY - data.start_page_y));
                    }
                    if (!data.is_scrolling && !data.active) {
                        e.preventDefault();
                        var direction = data.delta_x < 0 ? idx + 1 : idx - 1;
                        data.active = true;
                        self._goto(direction);
                    }
                }).on("touchend.fndtn.orbit", function(e) {
                    container.data("swipe-transition", {});
                    e.stopPropagation();
                });
            }
            container.on("mouseenter.fndtn.orbit", function(e) {
                if (settings.timer && settings.pause_on_hover) {
                    self.stop_timer();
                }
            }).on("mouseleave.fndtn.orbit", function(e) {
                if (settings.timer && settings.resume_on_mouseout) {
                    timer.start();
                }
            });
            $(document).on("click", "[data-orbit-link]", self.link_custom);
            $(window).on("load resize", self.compute_dimensions);
            Foundation.utils.image_loaded(this.slides().children("img"), self.compute_dimensions);
            Foundation.utils.image_loaded(this.slides().children("img"), function() {
                container.prev("." + settings.preloader_class).css("display", "none");
                self.update_slide_number(0);
                self.update_active_link(0);
                slides_container.trigger("ready.fndtn.orbit");
            });
        };
        self.init();
    };
    var Timer = function(el, settings, callback) {
        var self = this, duration = settings.timer_speed, progress = el.find("." + settings.timer_progress_class), start, timeout, left = -1;
        this.update_progress = function(w) {
            var new_progress = progress.clone();
            new_progress.attr("style", "");
            new_progress.css("width", w + "%");
            progress.replaceWith(new_progress);
            progress = new_progress;
        };
        this.restart = function() {
            clearTimeout(timeout);
            el.addClass(settings.timer_paused_class);
            left = -1;
            self.update_progress(0);
        };
        this.start = function() {
            if (!el.hasClass(settings.timer_paused_class)) {
                return true;
            }
            left = left === -1 ? duration : left;
            el.removeClass(settings.timer_paused_class);
            start = new Date().getTime();
            progress.animate({
                width: "100%"
            }, left, "linear");
            timeout = setTimeout(function() {
                self.restart();
                callback();
            }, left);
            el.trigger("timer-started.fndtn.orbit");
        };
        this.stop = function() {
            if (el.hasClass(settings.timer_paused_class)) {
                return true;
            }
            clearTimeout(timeout);
            el.addClass(settings.timer_paused_class);
            var end = new Date().getTime();
            left = left - (end - start);
            var w = 100 - left / duration * 100;
            self.update_progress(w);
            el.trigger("timer-stopped.fndtn.orbit");
        };
    };
    var SlideAnimation = function(settings, container) {
        var duration = settings.animation_speed;
        var is_rtl = $("html[dir=rtl]").length === 1;
        var margin = is_rtl ? "marginRight" : "marginLeft";
        var animMargin = {};
        animMargin[margin] = "0%";
        this.next = function(current, next, callback) {
            current.animate({
                marginLeft: "-100%"
            }, duration);
            next.animate(animMargin, duration, function() {
                current.css(margin, "100%");
                callback();
            });
        };
        this.prev = function(current, prev, callback) {
            current.animate({
                marginLeft: "100%"
            }, duration);
            prev.css(margin, "-100%");
            prev.animate(animMargin, duration, function() {
                current.css(margin, "100%");
                callback();
            });
        };
    };
    var FadeAnimation = function(settings, container) {
        var duration = settings.animation_speed;
        var is_rtl = $("html[dir=rtl]").length === 1;
        var margin = is_rtl ? "marginRight" : "marginLeft";
        this.next = function(current, next, callback) {
            next.css({
                margin: "0%",
                opacity: "0.01"
            });
            next.animate({
                opacity: "1"
            }, duration, "linear", function() {
                current.css("margin", "100%");
                callback();
            });
        };
        this.prev = function(current, prev, callback) {
            prev.css({
                margin: "0%",
                opacity: "0.01"
            });
            prev.animate({
                opacity: "1"
            }, duration, "linear", function() {
                current.css("margin", "100%");
                callback();
            });
        };
    };
    Foundation.libs = Foundation.libs || {};
    Foundation.libs.orbit = {
        name: "orbit",
        version: "5.5.3",
        settings: {
            animation: "slide",
            timer_speed: 1e4,
            pause_on_hover: true,
            resume_on_mouseout: false,
            next_on_click: true,
            animation_speed: 500,
            stack_on_small: false,
            navigation_arrows: true,
            slide_number: true,
            slide_number_text: "of",
            container_class: "orbit-container",
            stack_on_small_class: "orbit-stack-on-small",
            next_class: "orbit-next",
            prev_class: "orbit-prev",
            timer_container_class: "orbit-timer",
            timer_paused_class: "paused",
            timer_progress_class: "orbit-progress",
            slides_container_class: "orbit-slides-container",
            preloader_class: "preloader",
            slide_selector: "*",
            bullets_container_class: "orbit-bullets",
            bullets_active_class: "active",
            slide_number_class: "orbit-slide-number",
            caption_class: "orbit-caption",
            active_slide_class: "active",
            orbit_transition_class: "orbit-transitioning",
            bullets: true,
            circular: true,
            timer: true,
            variable_height: false,
            swipe: true,
            before_slide_change: noop,
            after_slide_change: noop
        },
        init: function(scope, method, options) {
            var self = this;
            this.bindings(method, options);
        },
        events: function(instance) {
            var orbit_instance = new Orbit(this.S(instance), this.S(instance).data("orbit-init"));
            this.S(instance).data(this.name + "-instance", orbit_instance);
        },
        reflow: function() {
            var self = this;
            if (self.S(self.scope).is("[data-orbit]")) {
                var $el = self.S(self.scope);
                var instance = $el.data(self.name + "-instance");
                instance.compute_dimensions();
            } else {
                self.S("[data-orbit]", self.scope).each(function(idx, el) {
                    var $el = self.S(el);
                    var opts = self.data_options($el);
                    var instance = $el.data(self.name + "-instance");
                    instance.compute_dimensions();
                });
            }
        }
    };
})(jQuery, window, window.document);

(function($, window, document, undefined) {
    "use strict";
    var openModals = [];
    Foundation.libs.reveal = {
        name: "reveal",
        version: "5.5.3",
        locked: false,
        settings: {
            animation: "fadeAndPop",
            animation_speed: 250,
            close_on_background_click: true,
            close_on_esc: true,
            dismiss_modal_class: "close-reveal-modal",
            multiple_opened: false,
            bg_class: "reveal-modal-bg",
            root_element: "body",
            open: function() {},
            opened: function() {},
            close: function() {},
            closed: function() {},
            on_ajax_error: $.noop,
            bg: $(".reveal-modal-bg"),
            css: {
                open: {
                    opacity: 0,
                    visibility: "visible",
                    display: "block"
                },
                close: {
                    opacity: 1,
                    visibility: "hidden",
                    display: "none"
                }
            }
        },
        init: function(scope, method, options) {
            $.extend(true, this.settings, method, options);
            this.bindings(method, options);
        },
        events: function(scope) {
            var self = this, S = self.S;
            S(this.scope).off(".reveal").on("click.fndtn.reveal", "[" + this.add_namespace("data-reveal-id") + "]:not([disabled])", function(e) {
                e.preventDefault();
                if (!self.locked) {
                    var element = S(this), ajax = element.data(self.data_attr("reveal-ajax")), replaceContentSel = element.data(self.data_attr("reveal-replace-content"));
                    self.locked = true;
                    if (typeof ajax === "undefined") {
                        self.open.call(self, element);
                    } else {
                        var url = ajax === true ? element.attr("href") : ajax;
                        self.open.call(self, element, {
                            url: url
                        }, {
                            replaceContentSel: replaceContentSel
                        });
                    }
                }
            });
            S(document).on("click.fndtn.reveal", this.close_targets(), function(e) {
                e.preventDefault();
                if (!self.locked) {
                    var settings = S("[" + self.attr_name() + "].open").data(self.attr_name(true) + "-init") || self.settings, bg_clicked = S(e.target)[0] === S("." + settings.bg_class)[0];
                    if (bg_clicked) {
                        if (settings.close_on_background_click) {
                            e.stopPropagation();
                        } else {
                            return;
                        }
                    }
                    self.locked = true;
                    self.close.call(self, bg_clicked ? S("[" + self.attr_name() + "].open:not(.toback)") : S(this).closest("[" + self.attr_name() + "]"));
                }
            });
            if (S("[" + self.attr_name() + "]", this.scope).length > 0) {
                S(this.scope).on("open.fndtn.reveal", this.settings.open).on("opened.fndtn.reveal", this.settings.opened).on("opened.fndtn.reveal", this.open_video).on("close.fndtn.reveal", this.settings.close).on("closed.fndtn.reveal", this.settings.closed).on("closed.fndtn.reveal", this.close_video);
            } else {
                S(this.scope).on("open.fndtn.reveal", "[" + self.attr_name() + "]", this.settings.open).on("opened.fndtn.reveal", "[" + self.attr_name() + "]", this.settings.opened).on("opened.fndtn.reveal", "[" + self.attr_name() + "]", this.open_video).on("close.fndtn.reveal", "[" + self.attr_name() + "]", this.settings.close).on("closed.fndtn.reveal", "[" + self.attr_name() + "]", this.settings.closed).on("closed.fndtn.reveal", "[" + self.attr_name() + "]", this.close_video);
            }
            return true;
        },
        key_up_on: function(scope) {
            var self = this;
            self.S("body").off("keyup.fndtn.reveal").on("keyup.fndtn.reveal", function(event) {
                var open_modal = self.S("[" + self.attr_name() + "].open"), settings = open_modal.data(self.attr_name(true) + "-init") || self.settings;
                if (settings && event.which === 27 && settings.close_on_esc && !self.locked) {
                    self.close.call(self, open_modal);
                }
            });
            return true;
        },
        key_up_off: function(scope) {
            this.S("body").off("keyup.fndtn.reveal");
            return true;
        },
        open: function(target, ajax_settings) {
            var self = this, modal;
            if (target) {
                if (typeof target.selector !== "undefined") {
                    modal = self.S("#" + target.data(self.data_attr("reveal-id"))).first();
                } else {
                    modal = self.S(this.scope);
                    ajax_settings = target;
                }
            } else {
                modal = self.S(this.scope);
            }
            var settings = modal.data(self.attr_name(true) + "-init");
            settings = settings || this.settings;
            if (modal.hasClass("open") && target !== undefined && target.attr("data-reveal-id") == modal.attr("id")) {
                return self.close(modal);
            }
            if (!modal.hasClass("open")) {
                var open_modal = self.S("[" + self.attr_name() + "].open");
                if (typeof modal.data("css-top") === "undefined") {
                    modal.data("css-top", parseInt(modal.css("top"), 10)).data("offset", this.cache_offset(modal));
                }
                modal.attr("tabindex", "0").attr("aria-hidden", "false");
                this.key_up_on(modal);
                modal.on("open.fndtn.reveal", function(e) {
                    if (e.namespace !== "fndtn.reveal") return;
                });
                modal.on("open.fndtn.reveal").trigger("open.fndtn.reveal");
                if (open_modal.length < 1) {
                    this.toggle_bg(modal, true);
                }
                if (typeof ajax_settings === "string") {
                    ajax_settings = {
                        url: ajax_settings
                    };
                }
                var openModal = function() {
                    if (open_modal.length > 0) {
                        if (settings.multiple_opened) {
                            self.to_back(open_modal);
                        } else {
                            self.hide(open_modal, settings.css.close);
                        }
                    }
                    if (settings.multiple_opened) {
                        openModals.push(modal);
                    }
                    self.show(modal, settings.css.open);
                };
                if (typeof ajax_settings === "undefined" || !ajax_settings.url) {
                    openModal();
                } else {
                    var old_success = typeof ajax_settings.success !== "undefined" ? ajax_settings.success : null;
                    $.extend(ajax_settings, {
                        success: function(data, textStatus, jqXHR) {
                            if ($.isFunction(old_success)) {
                                var result = old_success(data, textStatus, jqXHR);
                                if (typeof result == "string") {
                                    data = result;
                                }
                            }
                            if (typeof options !== "undefined" && typeof options.replaceContentSel !== "undefined") {
                                modal.find(options.replaceContentSel).html(data);
                            } else {
                                modal.html(data);
                            }
                            self.S(modal).foundation("section", "reflow");
                            self.S(modal).children().foundation();
                            openModal();
                        }
                    });
                    if (settings.on_ajax_error !== $.noop) {
                        $.extend(ajax_settings, {
                            error: settings.on_ajax_error
                        });
                    }
                    $.ajax(ajax_settings);
                }
            }
            self.S(window).trigger("resize");
        },
        close: function(modal) {
            var modal = modal && modal.length ? modal : this.S(this.scope), open_modals = this.S("[" + this.attr_name() + "].open"), settings = modal.data(this.attr_name(true) + "-init") || this.settings, self = this;
            if (open_modals.length > 0) {
                modal.removeAttr("tabindex", "0").attr("aria-hidden", "true");
                this.locked = true;
                this.key_up_off(modal);
                modal.trigger("close.fndtn.reveal");
                if (settings.multiple_opened && open_modals.length === 1 || !settings.multiple_opened || modal.length > 1) {
                    self.toggle_bg(modal, false);
                    self.to_front(modal);
                }
                if (settings.multiple_opened) {
                    var isCurrent = modal.is(":not(.toback)");
                    self.hide(modal, settings.css.close, settings);
                    if (isCurrent) {
                        openModals.pop();
                    } else {
                        openModals = $.grep(openModals, function(elt) {
                            var isThis = elt[0] === modal[0];
                            if (isThis) {
                                self.to_front(modal);
                            }
                            return !isThis;
                        });
                    }
                    if (openModals.length > 0) {
                        self.to_front(openModals[openModals.length - 1]);
                    }
                } else {
                    self.hide(open_modals, settings.css.close, settings);
                }
            }
        },
        close_targets: function() {
            var base = "." + this.settings.dismiss_modal_class;
            if (this.settings.close_on_background_click) {
                return base + ", ." + this.settings.bg_class;
            }
            return base;
        },
        toggle_bg: function(modal, state) {
            if (this.S("." + this.settings.bg_class).length === 0) {
                this.settings.bg = $("<div />", {
                    "class": this.settings.bg_class
                }).appendTo("body").hide();
            }
            var visible = this.settings.bg.filter(":visible").length > 0;
            if (state != visible) {
                if (state == undefined ? visible : !state) {
                    this.hide(this.settings.bg);
                } else {
                    this.show(this.settings.bg);
                }
            }
        },
        show: function(el, css) {
            if (css) {
                var settings = el.data(this.attr_name(true) + "-init") || this.settings, root_element = settings.root_element, context = this;
                if (el.parent(root_element).length === 0) {
                    var placeholder = el.wrap('<div style="display: none;" />').parent();
                    el.on("closed.fndtn.reveal.wrapped", function() {
                        el.detach().appendTo(placeholder);
                        el.unwrap().unbind("closed.fndtn.reveal.wrapped");
                    });
                    el.detach().appendTo(root_element);
                }
                var animData = getAnimationData(settings.animation);
                if (!animData.animate) {
                    this.locked = false;
                }
                if (animData.pop) {
                    css.top = $(window).scrollTop() - el.data("offset") + "px";
                    var end_css = {
                        top: $(window).scrollTop() + el.data("css-top") + "px",
                        opacity: 1
                    };
                    return setTimeout(function() {
                        return el.css(css).animate(end_css, settings.animation_speed, "linear", function() {
                            context.locked = false;
                            el.trigger("opened.fndtn.reveal");
                        }).addClass("open");
                    }, settings.animation_speed / 2);
                }
                css.top = $(window).scrollTop() + el.data("css-top") + "px";
                if (animData.fade) {
                    var end_css = {
                        opacity: 1
                    };
                    return setTimeout(function() {
                        return el.css(css).animate(end_css, settings.animation_speed, "linear", function() {
                            context.locked = false;
                            el.trigger("opened.fndtn.reveal");
                        }).addClass("open");
                    }, settings.animation_speed / 2);
                }
                return el.css(css).show().css({
                    opacity: 1
                }).addClass("open").trigger("opened.fndtn.reveal");
            }
            var settings = this.settings;
            if (getAnimationData(settings.animation).fade) {
                return el.fadeIn(settings.animation_speed / 2);
            }
            this.locked = false;
            return el.show();
        },
        to_back: function(el) {
            el.addClass("toback");
        },
        to_front: function(el) {
            el.removeClass("toback");
        },
        hide: function(el, css) {
            if (css) {
                var settings = el.data(this.attr_name(true) + "-init"), context = this;
                settings = settings || this.settings;
                var animData = getAnimationData(settings.animation);
                if (!animData.animate) {
                    this.locked = false;
                }
                if (animData.pop) {
                    var end_css = {
                        top: -$(window).scrollTop() - el.data("offset") + "px",
                        opacity: 0
                    };
                    return setTimeout(function() {
                        return el.animate(end_css, settings.animation_speed, "linear", function() {
                            context.locked = false;
                            el.css(css).trigger("closed.fndtn.reveal");
                        }).removeClass("open");
                    }, settings.animation_speed / 2);
                }
                if (animData.fade) {
                    var end_css = {
                        opacity: 0
                    };
                    return setTimeout(function() {
                        return el.animate(end_css, settings.animation_speed, "linear", function() {
                            context.locked = false;
                            el.css(css).trigger("closed.fndtn.reveal");
                        }).removeClass("open");
                    }, settings.animation_speed / 2);
                }
                return el.hide().css(css).removeClass("open").trigger("closed.fndtn.reveal");
            }
            var settings = this.settings;
            if (getAnimationData(settings.animation).fade) {
                return el.fadeOut(settings.animation_speed / 2);
            }
            return el.hide();
        },
        close_video: function(e) {
            var video = $(".flex-video", e.target), iframe = $("iframe", video);
            if (iframe.length > 0) {
                iframe.attr("data-src", iframe[0].src);
                iframe.attr("src", iframe.attr("src"));
                video.hide();
            }
        },
        open_video: function(e) {
            var video = $(".flex-video", e.target), iframe = video.find("iframe");
            if (iframe.length > 0) {
                var data_src = iframe.attr("data-src");
                if (typeof data_src === "string") {
                    iframe[0].src = iframe.attr("data-src");
                } else {
                    var src = iframe[0].src;
                    iframe[0].src = undefined;
                    iframe[0].src = src;
                }
                video.show();
            }
        },
        data_attr: function(str) {
            if (this.namespace.length > 0) {
                return this.namespace + "-" + str;
            }
            return str;
        },
        cache_offset: function(modal) {
            var offset = modal.show().height() + parseInt(modal.css("top"), 10) + modal.scrollY;
            modal.hide();
            return offset;
        },
        off: function() {
            $(this.scope).off(".fndtn.reveal");
        },
        reflow: function() {}
    };
    function getAnimationData(str) {
        var fade = /fade/i.test(str);
        var pop = /pop/i.test(str);
        return {
            animate: fade || pop,
            pop: pop,
            fade: fade
        };
    }
})(jQuery, window, window.document);

(function($, window, document, undefined) {
    "use strict";
    Foundation.libs.slider = {
        name: "slider",
        version: "5.5.3",
        settings: {
            start: 0,
            end: 100,
            step: 1,
            precision: 2,
            initial: null,
            display_selector: "",
            vertical: false,
            trigger_input_change: false,
            on_change: function() {}
        },
        cache: {},
        init: function(scope, method, options) {
            Foundation.inherit(this, "throttle");
            this.bindings(method, options);
            this.reflow();
        },
        events: function() {
            var self = this;
            $(this.scope).off(".slider").on("mousedown.fndtn.slider touchstart.fndtn.slider pointerdown.fndtn.slider", "[" + self.attr_name() + "]:not(.disabled, [disabled]) .range-slider-handle", function(e) {
                if (!self.cache.active) {
                    e.preventDefault();
                    self.set_active_slider($(e.target));
                }
            }).on("mousemove.fndtn.slider touchmove.fndtn.slider pointermove.fndtn.slider", function(e) {
                if (!!self.cache.active) {
                    e.preventDefault();
                    if ($.data(self.cache.active[0], "settings").vertical) {
                        var scroll_offset = 0;
                        if (!e.pageY) {
                            scroll_offset = window.scrollY;
                        }
                        self.calculate_position(self.cache.active, self.get_cursor_position(e, "y") + scroll_offset);
                    } else {
                        self.calculate_position(self.cache.active, self.get_cursor_position(e, "x"));
                    }
                }
            }).on("mouseup.fndtn.slider touchend.fndtn.slider pointerup.fndtn.slider", function(e) {
                if (!self.cache.active) {
                    var slider = $(e.target).attr("role") === "slider" ? $(e.target) : $(e.target).closest(".range-slider").find("[role='slider']");
                    if (slider.length && (!slider.parent().hasClass("disabled") && !slider.parent().attr("disabled"))) {
                        self.set_active_slider(slider);
                        if ($.data(self.cache.active[0], "settings").vertical) {
                            var scroll_offset = 0;
                            if (!e.pageY) {
                                scroll_offset = window.scrollY;
                            }
                            self.calculate_position(self.cache.active, self.get_cursor_position(e, "y") + scroll_offset);
                        } else {
                            self.calculate_position(self.cache.active, self.get_cursor_position(e, "x"));
                        }
                    }
                }
                self.remove_active_slider();
            }).on("change.fndtn.slider", function(e) {
                self.settings.on_change();
            });
            self.S(window).on("resize.fndtn.slider", self.throttle(function(e) {
                self.reflow();
            }, 300));
            this.S("[" + this.attr_name() + "]").each(function() {
                var slider = $(this), handle = slider.children(".range-slider-handle")[0], settings = self.initialize_settings(handle);
                if (settings.display_selector != "") {
                    $(settings.display_selector).each(function() {
                        if ($(this).attr("value")) {
                            $(this).off("change").on("change", function() {
                                slider.foundation("slider", "set_value", $(this).val());
                            });
                        }
                    });
                }
            });
        },
        get_cursor_position: function(e, xy) {
            var pageXY = "page" + xy.toUpperCase(), clientXY = "client" + xy.toUpperCase(), position;
            if (typeof e[pageXY] !== "undefined") {
                position = e[pageXY];
            } else if (typeof e.originalEvent[clientXY] !== "undefined") {
                position = e.originalEvent[clientXY];
            } else if (e.originalEvent.touches && e.originalEvent.touches[0] && typeof e.originalEvent.touches[0][clientXY] !== "undefined") {
                position = e.originalEvent.touches[0][clientXY];
            } else if (e.currentPoint && typeof e.currentPoint[xy] !== "undefined") {
                position = e.currentPoint[xy];
            }
            return position;
        },
        set_active_slider: function($handle) {
            this.cache.active = $handle;
        },
        remove_active_slider: function() {
            this.cache.active = null;
        },
        calculate_position: function($handle, cursor_x) {
            var self = this, settings = $.data($handle[0], "settings"), handle_l = $.data($handle[0], "handle_l"), handle_o = $.data($handle[0], "handle_o"), bar_l = $.data($handle[0], "bar_l"), bar_o = $.data($handle[0], "bar_o");
            requestAnimationFrame(function() {
                var pct;
                if (Foundation.rtl && !settings.vertical) {
                    pct = self.limit_to((bar_o + bar_l - cursor_x) / bar_l, 0, 1);
                } else {
                    pct = self.limit_to((cursor_x - bar_o) / bar_l, 0, 1);
                }
                pct = settings.vertical ? 1 - pct : pct;
                var norm = self.normalized_value(pct, settings.start, settings.end, settings.step, settings.precision);
                self.set_ui($handle, norm);
            });
        },
        set_ui: function($handle, value) {
            var settings = $.data($handle[0], "settings"), handle_l = $.data($handle[0], "handle_l"), bar_l = $.data($handle[0], "bar_l"), norm_pct = this.normalized_percentage(value, settings.start, settings.end), handle_offset = norm_pct * (bar_l - handle_l) - 1, progress_bar_length = norm_pct * 100, $handle_parent = $handle.parent(), $hidden_inputs = $handle.parent().children("input[type=hidden]");
            if (Foundation.rtl && !settings.vertical) {
                handle_offset = -handle_offset;
            }
            handle_offset = settings.vertical ? -handle_offset + bar_l - handle_l + 1 : handle_offset;
            this.set_translate($handle, handle_offset, settings.vertical);
            if (settings.vertical) {
                $handle.siblings(".range-slider-active-segment").css("height", progress_bar_length + "%");
            } else {
                $handle.siblings(".range-slider-active-segment").css("width", progress_bar_length + "%");
            }
            $handle_parent.attr(this.attr_name(), value).trigger("change.fndtn.slider");
            $hidden_inputs.val(value);
            if (settings.trigger_input_change) {
                $hidden_inputs.trigger("change.fndtn.slider");
            }
            if (!$handle[0].hasAttribute("aria-valuemin")) {
                $handle.attr({
                    "aria-valuemin": settings.start,
                    "aria-valuemax": settings.end
                });
            }
            $handle.attr("aria-valuenow", value);
            if (settings.display_selector != "") {
                $(settings.display_selector).each(function() {
                    if (this.hasAttribute("value")) {
                        $(this).val(value);
                    } else {
                        $(this).text(value);
                    }
                });
            }
        },
        normalized_percentage: function(val, start, end) {
            return Math.min(1, (val - start) / (end - start));
        },
        normalized_value: function(val, start, end, step, precision) {
            var range = end - start, point = val * range, mod = (point - point % step) / step, rem = point % step, round = rem >= step * .5 ? step : 0;
            return (mod * step + round + start).toFixed(precision);
        },
        set_translate: function(ele, offset, vertical) {
            if (vertical) {
                $(ele).css("-webkit-transform", "translateY(" + offset + "px)").css("-moz-transform", "translateY(" + offset + "px)").css("-ms-transform", "translateY(" + offset + "px)").css("-o-transform", "translateY(" + offset + "px)").css("transform", "translateY(" + offset + "px)");
            } else {
                $(ele).css("-webkit-transform", "translateX(" + offset + "px)").css("-moz-transform", "translateX(" + offset + "px)").css("-ms-transform", "translateX(" + offset + "px)").css("-o-transform", "translateX(" + offset + "px)").css("transform", "translateX(" + offset + "px)");
            }
        },
        limit_to: function(val, min, max) {
            return Math.min(Math.max(val, min), max);
        },
        initialize_settings: function(handle) {
            var settings = $.extend({}, this.settings, this.data_options($(handle).parent())), decimal_places_match_result;
            if (settings.precision === null) {
                decimal_places_match_result = ("" + settings.step).match(/\.([\d]*)/);
                settings.precision = decimal_places_match_result && decimal_places_match_result[1] ? decimal_places_match_result[1].length : 0;
            }
            if (settings.vertical) {
                $.data(handle, "bar_o", $(handle).parent().offset().top);
                $.data(handle, "bar_l", $(handle).parent().outerHeight());
                $.data(handle, "handle_o", $(handle).offset().top);
                $.data(handle, "handle_l", $(handle).outerHeight());
            } else {
                $.data(handle, "bar_o", $(handle).parent().offset().left);
                $.data(handle, "bar_l", $(handle).parent().outerWidth());
                $.data(handle, "handle_o", $(handle).offset().left);
                $.data(handle, "handle_l", $(handle).outerWidth());
            }
            $.data(handle, "bar", $(handle).parent());
            return $.data(handle, "settings", settings);
        },
        set_initial_position: function($ele) {
            var settings = $.data($ele.children(".range-slider-handle")[0], "settings"), initial = typeof settings.initial == "number" && !isNaN(settings.initial) ? settings.initial : Math.floor((settings.end - settings.start) * .5 / settings.step) * settings.step + settings.start, $handle = $ele.children(".range-slider-handle");
            this.set_ui($handle, initial);
        },
        set_value: function(value) {
            var self = this;
            $("[" + self.attr_name() + "]", this.scope).each(function() {
                $(this).attr(self.attr_name(), value);
            });
            if (!!$(this.scope).attr(self.attr_name())) {
                $(this.scope).attr(self.attr_name(), value);
            }
            self.reflow();
        },
        reflow: function() {
            var self = this;
            self.S("[" + this.attr_name() + "]").each(function() {
                var handle = $(this).children(".range-slider-handle")[0], val = $(this).attr(self.attr_name());
                self.initialize_settings(handle);
                if (val) {
                    self.set_ui($(handle), parseFloat(val));
                } else {
                    self.set_initial_position($(this));
                }
            });
        }
    };
})(jQuery, window, window.document);

(function($, window, document, undefined) {
    "use strict";
    Foundation.libs.tab = {
        name: "tab",
        version: "5.5.3",
        settings: {
            active_class: "active",
            callback: function() {},
            deep_linking: false,
            scroll_to_content: true,
            is_hover: false
        },
        default_tab_hashes: [],
        init: function(scope, method, options) {
            var self = this, S = this.S;
            S("[" + this.attr_name() + "] > .active > a", this.scope).each(function() {
                self.default_tab_hashes.push(this.hash);
            });
            this.bindings(method, options);
            this.handle_location_hash_change();
        },
        events: function() {
            var self = this, S = this.S;
            var usual_tab_behavior = function(e, target) {
                var settings = S(target).closest("[" + self.attr_name() + "]").data(self.attr_name(true) + "-init");
                if (!settings.is_hover || Modernizr.touch) {
                    var keyCode = e.keyCode || e.which;
                    if (keyCode !== 9) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    self.toggle_active_tab(S(target).parent());
                }
            };
            S(this.scope).off(".tab").on("keydown.fndtn.tab", "[" + this.attr_name() + "] > * > a", function(e) {
                var keyCode = e.keyCode || e.which;
                if (keyCode === 13 || keyCode === 32) {
                    var el = this;
                    usual_tab_behavior(e, el);
                }
            }).on("click.fndtn.tab", "[" + this.attr_name() + "] > * > a", function(e) {
                var el = this;
                usual_tab_behavior(e, el);
            }).on("mouseenter.fndtn.tab", "[" + this.attr_name() + "] > * > a", function(e) {
                var settings = S(this).closest("[" + self.attr_name() + "]").data(self.attr_name(true) + "-init");
                if (settings.is_hover) {
                    self.toggle_active_tab(S(this).parent());
                }
            });
            S(window).on("hashchange.fndtn.tab", function(e) {
                e.preventDefault();
                self.handle_location_hash_change();
            });
        },
        handle_location_hash_change: function() {
            var self = this, S = this.S;
            S("[" + this.attr_name() + "]", this.scope).each(function() {
                var settings = S(this).data(self.attr_name(true) + "-init");
                if (settings.deep_linking) {
                    var hash;
                    if (settings.scroll_to_content) {
                        hash = self.scope.location.hash;
                    } else {
                        hash = self.scope.location.hash.replace("fndtn-", "");
                    }
                    if (hash != "") {
                        var hash_element = S(hash);
                        if (hash_element.hasClass("content") && hash_element.parent().hasClass("tabs-content")) {
                            self.toggle_active_tab($("[" + self.attr_name() + "] > * > a[href=" + hash + "]").parent());
                        } else {
                            var hash_tab_container_id = hash_element.closest(".content").attr("id");
                            if (hash_tab_container_id != undefined) {
                                self.toggle_active_tab($("[" + self.attr_name() + "] > * > a[href=#" + hash_tab_container_id + "]").parent(), hash);
                            }
                        }
                    } else {
                        for (var ind = 0; ind < self.default_tab_hashes.length; ind++) {
                            self.toggle_active_tab($("[" + self.attr_name() + "] > * > a[href=" + self.default_tab_hashes[ind] + "]").parent());
                        }
                    }
                }
            });
        },
        toggle_active_tab: function(tab, location_hash) {
            var self = this, S = self.S, tabs = tab.closest("[" + this.attr_name() + "]"), tab_link = tab.find("a"), anchor = tab.children("a").first(), target_hash = "#" + anchor.attr("href").split("#")[1], target = S(target_hash), siblings = tab.siblings(), settings = tabs.data(this.attr_name(true) + "-init"), interpret_keyup_action = function(e) {
                var $original = $(this);
                var $prev = $(this).parents("li").prev().children('[role="tab"]');
                var $next = $(this).parents("li").next().children('[role="tab"]');
                var $target;
                switch (e.keyCode) {
                  case 37:
                    $target = $prev;
                    break;

                  case 39:
                    $target = $next;
                    break;

                  default:
                    $target = false;
                    break;
                }
                if ($target.length) {
                    $original.attr({
                        tabindex: "-1",
                        "aria-selected": null
                    });
                    $target.attr({
                        tabindex: "0",
                        "aria-selected": true
                    }).focus();
                }
                $('[role="tabpanel"]').attr("aria-hidden", "true");
                $("#" + $(document.activeElement).attr("href").substring(1)).attr("aria-hidden", null);
            }, go_to_hash = function(hash) {
                var default_hash = settings.scroll_to_content ? self.default_tab_hashes[0] : "fndtn-" + self.default_tab_hashes[0].replace("#", "");
                if (hash !== default_hash || window.location.hash) {
                    window.location.hash = hash;
                }
            };
            if (anchor.data("tab-content")) {
                target_hash = "#" + anchor.data("tab-content").split("#")[1];
                target = S(target_hash);
            }
            if (settings.deep_linking) {
                if (settings.scroll_to_content) {
                    go_to_hash(location_hash || target_hash);
                    if (location_hash == undefined || location_hash == target_hash) {
                        tab.parent()[0].scrollIntoView();
                    } else {
                        S(target_hash)[0].scrollIntoView();
                    }
                } else {
                    if (location_hash != undefined) {
                        go_to_hash("fndtn-" + location_hash.replace("#", ""));
                    } else {
                        go_to_hash("fndtn-" + target_hash.replace("#", ""));
                    }
                }
            }
            tab.addClass(settings.active_class).triggerHandler("opened");
            tab_link.attr({
                "aria-selected": "true",
                tabindex: 0
            });
            siblings.removeClass(settings.active_class);
            siblings.find("a").attr({
                "aria-selected": "false"
            });
            target.siblings().removeClass(settings.active_class).attr({
                "aria-hidden": "true"
            });
            target.addClass(settings.active_class).attr("aria-hidden", "false").removeAttr("tabindex");
            settings.callback(tab);
            target.triggerHandler("toggled", [ target ]);
            tabs.triggerHandler("toggled", [ tab ]);
            tab_link.off("keydown").on("keydown", interpret_keyup_action);
        },
        data_attr: function(str) {
            if (this.namespace.length > 0) {
                return this.namespace + "-" + str;
            }
            return str;
        },
        off: function() {},
        reflow: function() {}
    };
})(jQuery, window, window.document);

(function($, window, document, undefined) {
    "use strict";
    Foundation.libs.tooltip = {
        name: "tooltip",
        version: "5.5.3",
        settings: {
            additional_inheritable_classes: [],
            tooltip_class: ".tooltip",
            append_to: "body",
            touch_close_text: "Tap To Close",
            disable_for_touch: false,
            hover_delay: 200,
            fade_in_duration: 150,
            fade_out_duration: 150,
            show_on: "all",
            tip_template: function(selector, content) {
                return '<span data-selector="' + selector + '" id="' + selector + '" class="' + Foundation.libs.tooltip.settings.tooltip_class.substring(1) + '" role="tooltip">' + content + '<span class="nub"></span></span>';
            }
        },
        cache: {},
        init: function(scope, method, options) {
            Foundation.inherit(this, "random_str");
            this.bindings(method, options);
        },
        should_show: function(target, tip) {
            var settings = $.extend({}, this.settings, this.data_options(target));
            if (settings.show_on === "all") {
                return true;
            } else if (this.small() && settings.show_on === "small") {
                return true;
            } else if (this.medium() && settings.show_on === "medium") {
                return true;
            } else if (this.large() && settings.show_on === "large") {
                return true;
            }
            return false;
        },
        medium: function() {
            return matchMedia(Foundation.media_queries["medium"]).matches;
        },
        large: function() {
            return matchMedia(Foundation.media_queries["large"]).matches;
        },
        events: function(instance) {
            var self = this, S = self.S;
            self.create(this.S(instance));
            function _startShow(elt, $this, immediate) {
                if (elt.timer) {
                    return;
                }
                if (immediate) {
                    elt.timer = null;
                    self.showTip($this);
                } else {
                    elt.timer = setTimeout(function() {
                        elt.timer = null;
                        self.showTip($this);
                    }.bind(elt), self.settings.hover_delay);
                }
            }
            function _startHide(elt, $this) {
                if (elt.timer) {
                    clearTimeout(elt.timer);
                    elt.timer = null;
                }
                self.hide($this);
            }
            $(this.scope).off(".tooltip").on("mouseenter.fndtn.tooltip mouseleave.fndtn.tooltip touchstart.fndtn.tooltip MSPointerDown.fndtn.tooltip", "[" + this.attr_name() + "]", function(e) {
                var $this = S(this), settings = $.extend({}, self.settings, self.data_options($this)), is_touch = false;
                if (Modernizr.touch && /touchstart|MSPointerDown/i.test(e.type) && S(e.target).is("a")) {
                    return false;
                }
                if (/mouse/i.test(e.type) && self.ie_touch(e)) {
                    return false;
                }
                if ($this.hasClass("open")) {
                    if (Modernizr.touch && /touchstart|MSPointerDown/i.test(e.type)) {
                        e.preventDefault();
                    }
                    self.hide($this);
                } else {
                    if (settings.disable_for_touch && Modernizr.touch && /touchstart|MSPointerDown/i.test(e.type)) {
                        return;
                    } else if (!settings.disable_for_touch && Modernizr.touch && /touchstart|MSPointerDown/i.test(e.type)) {
                        e.preventDefault();
                        S(settings.tooltip_class + ".open").hide();
                        is_touch = true;
                        if ($(".open[" + self.attr_name() + "]").length > 0) {
                            var prevOpen = S($(".open[" + self.attr_name() + "]")[0]);
                            self.hide(prevOpen);
                        }
                    }
                    if (/enter|over/i.test(e.type)) {
                        _startShow(this, $this);
                    } else if (e.type === "mouseout" || e.type === "mouseleave") {
                        _startHide(this, $this);
                    } else {
                        _startShow(this, $this, true);
                    }
                }
            }).on("mouseleave.fndtn.tooltip touchstart.fndtn.tooltip MSPointerDown.fndtn.tooltip", "[" + this.attr_name() + "].open", function(e) {
                if (/mouse/i.test(e.type) && self.ie_touch(e)) {
                    return false;
                }
                if ($(this).data("tooltip-open-event-type") == "touch" && e.type == "mouseleave") {
                    return;
                } else if ($(this).data("tooltip-open-event-type") == "mouse" && /MSPointerDown|touchstart/i.test(e.type)) {
                    self.convert_to_touch($(this));
                } else {
                    _startHide(this, $(this));
                }
            }).on("DOMNodeRemoved DOMAttrModified", "[" + this.attr_name() + "]:not(a)", function(e) {
                _startHide(this, S(this));
            });
        },
        ie_touch: function(e) {
            return false;
        },
        showTip: function($target) {
            var $tip = this.getTip($target);
            if (this.should_show($target, $tip)) {
                return this.show($target);
            }
            return;
        },
        getTip: function($target) {
            var selector = this.selector($target), settings = $.extend({}, this.settings, this.data_options($target)), tip = null;
            if (selector) {
                tip = this.S('span[data-selector="' + selector + '"]' + settings.tooltip_class);
            }
            return typeof tip === "object" ? tip : false;
        },
        selector: function($target) {
            var dataSelector = $target.attr(this.attr_name()) || $target.attr("data-selector");
            if (typeof dataSelector != "string") {
                dataSelector = this.random_str(6);
                $target.attr("data-selector", dataSelector).attr("aria-describedby", dataSelector);
            }
            return dataSelector;
        },
        create: function($target) {
            var self = this, settings = $.extend({}, this.settings, this.data_options($target)), tip_template = this.settings.tip_template;
            if (typeof settings.tip_template === "string" && window.hasOwnProperty(settings.tip_template)) {
                tip_template = window[settings.tip_template];
            }
            var $tip = $(tip_template(this.selector($target), $("<div></div>").html($target.attr("title")).html())), classes = this.inheritable_classes($target);
            $tip.addClass(classes).appendTo(settings.append_to);
            if (Modernizr.touch) {
                $tip.append('<span class="tap-to-close">' + settings.touch_close_text + "</span>");
                $tip.on("touchstart.fndtn.tooltip MSPointerDown.fndtn.tooltip", function(e) {
                    self.hide($target);
                });
            }
            $target.removeAttr("title").attr("title", "");
        },
        reposition: function(target, tip, classes) {
            var width, nub, nubHeight, nubWidth, objPos;
            tip.css("visibility", "hidden").show();
            width = target.data("width");
            nub = tip.children(".nub");
            nubHeight = nub.outerHeight();
            nubWidth = nub.outerWidth();
            if (this.small()) {
                tip.css({
                    width: "100%"
                });
            } else {
                tip.css({
                    width: width ? width : "auto"
                });
            }
            objPos = function(obj, top, right, bottom, left, width) {
                return obj.css({
                    top: top ? top : "auto",
                    bottom: bottom ? bottom : "auto",
                    left: left ? left : "auto",
                    right: right ? right : "auto"
                }).end();
            };
            var o_top = target.offset().top;
            var o_left = target.offset().left;
            var outerHeight = target.outerHeight();
            objPos(tip, o_top + outerHeight + 10, "auto", "auto", o_left);
            if (this.small()) {
                objPos(tip, o_top + outerHeight + 10, "auto", "auto", 12.5, $(this.scope).width());
                tip.addClass("tip-override");
                objPos(nub, -nubHeight, "auto", "auto", o_left);
            } else {
                if (Foundation.rtl) {
                    nub.addClass("rtl");
                    o_left = o_left + target.outerWidth() - tip.outerWidth();
                }
                objPos(tip, o_top + outerHeight + 10, "auto", "auto", o_left);
                if (nub.attr("style")) {
                    nub.removeAttr("style");
                }
                tip.removeClass("tip-override");
                var tip_outerHeight = tip.outerHeight();
                if (classes && classes.indexOf("tip-top") > -1) {
                    if (Foundation.rtl) {
                        nub.addClass("rtl");
                    }
                    objPos(tip, o_top - tip_outerHeight, "auto", "auto", o_left).removeClass("tip-override");
                } else if (classes && classes.indexOf("tip-left") > -1) {
                    objPos(tip, o_top + outerHeight / 2 - tip_outerHeight / 2, "auto", "auto", o_left - tip.outerWidth() - nubHeight).removeClass("tip-override");
                    nub.removeClass("rtl");
                } else if (classes && classes.indexOf("tip-right") > -1) {
                    objPos(tip, o_top + outerHeight / 2 - tip_outerHeight / 2, "auto", "auto", o_left + target.outerWidth() + nubHeight).removeClass("tip-override");
                    nub.removeClass("rtl");
                }
            }
            tip.css("visibility", "visible").hide();
        },
        small: function() {
            return matchMedia(Foundation.media_queries.small).matches && !matchMedia(Foundation.media_queries.medium).matches;
        },
        inheritable_classes: function($target) {
            var settings = $.extend({}, this.settings, this.data_options($target)), inheritables = [ "tip-top", "tip-left", "tip-bottom", "tip-right", "radius", "round" ].concat(settings.additional_inheritable_classes), classes = $target.attr("class"), filtered = classes ? $.map(classes.split(" "), function(el, i) {
                if ($.inArray(el, inheritables) !== -1) {
                    return el;
                }
            }).join(" ") : "";
            return $.trim(filtered);
        },
        convert_to_touch: function($target) {
            var self = this, $tip = self.getTip($target), settings = $.extend({}, self.settings, self.data_options($target));
            if ($tip.find(".tap-to-close").length === 0) {
                $tip.append('<span class="tap-to-close">' + settings.touch_close_text + "</span>");
                $tip.on("click.fndtn.tooltip.tapclose touchstart.fndtn.tooltip.tapclose MSPointerDown.fndtn.tooltip.tapclose", function(e) {
                    self.hide($target);
                });
            }
            $target.data("tooltip-open-event-type", "touch");
        },
        show: function($target) {
            var $tip = this.getTip($target);
            if ($target.data("tooltip-open-event-type") == "touch") {
                this.convert_to_touch($target);
            }
            this.reposition($target, $tip, $target.attr("class"));
            $target.addClass("open");
            $tip.fadeIn(this.settings.fade_in_duration);
        },
        hide: function($target) {
            var $tip = this.getTip($target);
            $tip.fadeOut(this.settings.fade_out_duration, function() {
                $tip.find(".tap-to-close").remove();
                $tip.off("click.fndtn.tooltip.tapclose MSPointerDown.fndtn.tapclose");
                $target.removeClass("open");
            });
        },
        off: function() {
            var self = this;
            this.S(this.scope).off(".fndtn.tooltip");
            this.S(this.settings.tooltip_class).each(function(i) {
                $("[" + self.attr_name() + "]").eq(i).attr("title", $(this).text());
            }).remove();
        },
        reflow: function() {}
    };
})(jQuery, window, window.document);

(function($, window, document, undefined) {
    "use strict";
    Foundation.libs.topbar = {
        name: "topbar",
        version: "5.5.3",
        settings: {
            index: 0,
            start_offset: 0,
            sticky_class: "sticky",
            custom_back_text: true,
            back_text: "Back",
            mobile_show_parent_link: true,
            is_hover: true,
            scrolltop: true,
            sticky_on: "all",
            dropdown_autoclose: true
        },
        init: function(section, method, options) {
            Foundation.inherit(this, "add_custom_rule register_media throttle");
            var self = this;
            self.register_media("topbar", "foundation-mq-topbar");
            this.bindings(method, options);
            self.S("[" + this.attr_name() + "]", this.scope).each(function() {
                var topbar = $(this), settings = topbar.data(self.attr_name(true) + "-init"), section = self.S("section, .top-bar-section", this);
                topbar.data("index", 0);
                var topbarContainer = topbar.parent();
                if (topbarContainer.hasClass("fixed") || self.is_sticky(topbar, topbarContainer, settings)) {
                    self.settings.sticky_class = settings.sticky_class;
                    self.settings.sticky_topbar = topbar;
                    topbar.data("height", topbarContainer.outerHeight());
                    topbar.data("stickyoffset", topbarContainer.offset().top);
                } else {
                    topbar.data("height", topbar.outerHeight());
                }
                if (!settings.assembled) {
                    self.assemble(topbar);
                }
                if (settings.is_hover) {
                    self.S(".has-dropdown", topbar).addClass("not-click");
                } else {
                    self.S(".has-dropdown", topbar).removeClass("not-click");
                }
                self.add_custom_rule(".f-topbar-fixed { padding-top: " + topbar.data("height") + "px }");
                if (topbarContainer.hasClass("fixed")) {
                    self.S("body").addClass("f-topbar-fixed");
                }
            });
        },
        is_sticky: function(topbar, topbarContainer, settings) {
            var sticky = topbarContainer.hasClass(settings.sticky_class);
            var smallMatch = matchMedia(Foundation.media_queries.small).matches;
            var medMatch = matchMedia(Foundation.media_queries.medium).matches;
            var lrgMatch = matchMedia(Foundation.media_queries.large).matches;
            if (sticky && settings.sticky_on === "all") {
                return true;
            }
            if (sticky && this.small() && settings.sticky_on.indexOf("small") !== -1) {
                if (smallMatch && !medMatch && !lrgMatch) {
                    return true;
                }
            }
            if (sticky && this.medium() && settings.sticky_on.indexOf("medium") !== -1) {
                if (smallMatch && medMatch && !lrgMatch) {
                    return true;
                }
            }
            if (sticky && this.large() && settings.sticky_on.indexOf("large") !== -1) {
                if (smallMatch && medMatch && lrgMatch) {
                    return true;
                }
            }
            return false;
        },
        toggle: function(toggleEl) {
            var self = this, topbar;
            if (toggleEl) {
                topbar = self.S(toggleEl).closest("[" + this.attr_name() + "]");
            } else {
                topbar = self.S("[" + this.attr_name() + "]");
            }
            var settings = topbar.data(this.attr_name(true) + "-init");
            var section = self.S("section, .top-bar-section", topbar);
            if (self.breakpoint()) {
                if (!self.rtl) {
                    section.css({
                        left: "0%"
                    });
                    $(">.name", section).css({
                        left: "100%"
                    });
                } else {
                    section.css({
                        right: "0%"
                    });
                    $(">.name", section).css({
                        right: "100%"
                    });
                }
                self.S("li.moved", section).removeClass("moved");
                topbar.data("index", 0);
                topbar.toggleClass("expanded").css("height", "");
            }
            if (settings.scrolltop) {
                if (!topbar.hasClass("expanded")) {
                    if (topbar.hasClass("fixed")) {
                        topbar.parent().addClass("fixed");
                        topbar.removeClass("fixed");
                        self.S("body").addClass("f-topbar-fixed");
                    }
                } else if (topbar.parent().hasClass("fixed")) {
                    if (settings.scrolltop) {
                        topbar.parent().removeClass("fixed");
                        topbar.addClass("fixed");
                        self.S("body").removeClass("f-topbar-fixed");
                        window.scrollTo(0, 0);
                    } else {
                        topbar.parent().removeClass("expanded");
                    }
                }
            } else {
                if (self.is_sticky(topbar, topbar.parent(), settings)) {
                    topbar.parent().addClass("fixed");
                }
                if (topbar.parent().hasClass("fixed")) {
                    if (!topbar.hasClass("expanded")) {
                        topbar.removeClass("fixed");
                        topbar.parent().removeClass("expanded");
                        self.update_sticky_positioning();
                    } else {
                        topbar.addClass("fixed");
                        topbar.parent().addClass("expanded");
                        self.S("body").addClass("f-topbar-fixed");
                    }
                }
            }
        },
        timer: null,
        events: function(bar) {
            var self = this, S = this.S;
            S(this.scope).off(".topbar").on("click.fndtn.topbar", "[" + this.attr_name() + "] .toggle-topbar", function(e) {
                e.preventDefault();
                self.toggle(this);
            }).on("click.fndtn.topbar contextmenu.fndtn.topbar", '.top-bar .top-bar-section li a[href^="#"],[' + this.attr_name() + '] .top-bar-section li a[href^="#"]', function(e) {
                var li = $(this).closest("li"), topbar = li.closest("[" + self.attr_name() + "]"), settings = topbar.data(self.attr_name(true) + "-init");
                if (settings.dropdown_autoclose && settings.is_hover) {
                    var hoverLi = $(this).closest(".hover");
                    hoverLi.removeClass("hover");
                }
                if (self.breakpoint() && !li.hasClass("back") && !li.hasClass("has-dropdown")) {
                    self.toggle();
                }
            }).on("click.fndtn.topbar", "[" + this.attr_name() + "] li.has-dropdown", function(e) {
                var li = S(this), target = S(e.target), topbar = li.closest("[" + self.attr_name() + "]"), settings = topbar.data(self.attr_name(true) + "-init");
                if (target.data("revealId")) {
                    self.toggle();
                    return;
                }
                if (self.breakpoint()) {
                    return;
                }
                if (settings.is_hover && !Modernizr.touch) {
                    return;
                }
                e.stopImmediatePropagation();
                if (li.hasClass("hover")) {
                    li.removeClass("hover").find("li").removeClass("hover");
                    li.parents("li.hover").removeClass("hover");
                } else {
                    li.addClass("hover");
                    $(li).siblings().removeClass("hover");
                    if (target[0].nodeName === "A" && target.parent().hasClass("has-dropdown")) {
                        e.preventDefault();
                    }
                }
            }).on("click.fndtn.topbar", "[" + this.attr_name() + "] .has-dropdown>a", function(e) {
                if (self.breakpoint()) {
                    e.preventDefault();
                    var $this = S(this), topbar = $this.closest("[" + self.attr_name() + "]"), section = topbar.find("section, .top-bar-section"), dropdownHeight = $this.next(".dropdown").outerHeight(), $selectedLi = $this.closest("li");
                    topbar.data("index", topbar.data("index") + 1);
                    $selectedLi.addClass("moved");
                    if (!self.rtl) {
                        section.css({
                            left: -(100 * topbar.data("index")) + "%"
                        });
                        section.find(">.name").css({
                            left: 100 * topbar.data("index") + "%"
                        });
                    } else {
                        section.css({
                            right: -(100 * topbar.data("index")) + "%"
                        });
                        section.find(">.name").css({
                            right: 100 * topbar.data("index") + "%"
                        });
                    }
                    topbar.css("height", $this.siblings("ul").outerHeight(true) + topbar.data("height"));
                }
            });
            S(window).off(".topbar").on("resize.fndtn.topbar", self.throttle(function() {
                self.resize.call(self);
            }, 50)).trigger("resize.fndtn.topbar").load(function() {
                S(this).trigger("resize.fndtn.topbar");
            });
            S("body").off(".topbar").on("click.fndtn.topbar", function(e) {
                var parent = S(e.target).closest("li").closest("li.hover");
                if (parent.length > 0) {
                    return;
                }
                S("[" + self.attr_name() + "] li.hover").removeClass("hover");
            });
            S(this.scope).on("click.fndtn.topbar", "[" + this.attr_name() + "] .has-dropdown .back", function(e) {
                e.preventDefault();
                var $this = S(this), topbar = $this.closest("[" + self.attr_name() + "]"), section = topbar.find("section, .top-bar-section"), settings = topbar.data(self.attr_name(true) + "-init"), $movedLi = $this.closest("li.moved"), $previousLevelUl = $movedLi.parent();
                topbar.data("index", topbar.data("index") - 1);
                if (!self.rtl) {
                    section.css({
                        left: -(100 * topbar.data("index")) + "%"
                    });
                    section.find(">.name").css({
                        left: 100 * topbar.data("index") + "%"
                    });
                } else {
                    section.css({
                        right: -(100 * topbar.data("index")) + "%"
                    });
                    section.find(">.name").css({
                        right: 100 * topbar.data("index") + "%"
                    });
                }
                if (topbar.data("index") === 0) {
                    topbar.css("height", "");
                } else {
                    topbar.css("height", $previousLevelUl.outerHeight(true) + topbar.data("height"));
                }
                setTimeout(function() {
                    $movedLi.removeClass("moved");
                }, 300);
            });
            S(this.scope).find(".dropdown a").focus(function() {
                $(this).parents(".has-dropdown").addClass("hover");
            }).blur(function() {
                $(this).parents(".has-dropdown").removeClass("hover");
            });
        },
        resize: function() {
            var self = this;
            self.S("[" + this.attr_name() + "]").each(function() {
                var topbar = self.S(this), settings = topbar.data(self.attr_name(true) + "-init");
                var stickyContainer = topbar.parent("." + self.settings.sticky_class);
                var stickyOffset;
                if (!self.breakpoint()) {
                    var doToggle = topbar.hasClass("expanded");
                    topbar.css("height", "").removeClass("expanded").find("li").removeClass("hover");
                    if (doToggle) {
                        self.toggle(topbar);
                    }
                }
                if (self.is_sticky(topbar, stickyContainer, settings)) {
                    if (stickyContainer.hasClass("fixed")) {
                        stickyContainer.removeClass("fixed");
                        stickyOffset = stickyContainer.offset().top;
                        if (self.S(document.body).hasClass("f-topbar-fixed")) {
                            stickyOffset -= topbar.data("height");
                        }
                        topbar.data("stickyoffset", stickyOffset);
                        stickyContainer.addClass("fixed");
                    } else {
                        stickyOffset = stickyContainer.offset().top;
                        topbar.data("stickyoffset", stickyOffset);
                    }
                }
            });
        },
        breakpoint: function() {
            return !matchMedia(Foundation.media_queries["topbar"]).matches;
        },
        small: function() {
            return matchMedia(Foundation.media_queries["small"]).matches;
        },
        medium: function() {
            return matchMedia(Foundation.media_queries["medium"]).matches;
        },
        large: function() {
            return matchMedia(Foundation.media_queries["large"]).matches;
        },
        assemble: function(topbar) {
            var self = this, settings = topbar.data(this.attr_name(true) + "-init"), section = self.S("section, .top-bar-section", topbar);
            section.detach();
            self.S(".has-dropdown>a", section).each(function() {
                var $link = self.S(this), $dropdown = $link.siblings(".dropdown"), url = $link.attr("href"), $titleLi;
                if (!$dropdown.find(".title.back").length) {
                    if (settings.mobile_show_parent_link == true && url) {
                        $titleLi = $('<li class="title back js-generated"><h5><a href="javascript:void(0)"></a></h5></li><li class="parent-link hide-for-medium-up"><a class="parent-link js-generated" href="' + url + '">' + $link.html() + "</a></li>");
                    } else {
                        $titleLi = $('<li class="title back js-generated"><h5><a href="javascript:void(0)"></a></h5>');
                    }
                    if (settings.custom_back_text == true) {
                        $("h5>a", $titleLi).html(settings.back_text);
                    } else {
                        $("h5>a", $titleLi).html("&laquo; " + $link.html());
                    }
                    $dropdown.prepend($titleLi);
                }
            });
            section.appendTo(topbar);
            this.sticky();
            this.assembled(topbar);
        },
        assembled: function(topbar) {
            topbar.data(this.attr_name(true), $.extend({}, topbar.data(this.attr_name(true)), {
                assembled: true
            }));
        },
        height: function(ul) {
            var total = 0, self = this;
            $("> li", ul).each(function() {
                total += self.S(this).outerHeight(true);
            });
            return total;
        },
        sticky: function() {
            var self = this;
            this.S(window).on("scroll", function() {
                self.update_sticky_positioning();
            });
        },
        update_sticky_positioning: function() {
            var klass = "." + this.settings.sticky_class, $window = this.S(window), self = this;
            if (self.settings.sticky_topbar && self.is_sticky(this.settings.sticky_topbar, this.settings.sticky_topbar.parent(), this.settings)) {
                var distance = this.settings.sticky_topbar.data("stickyoffset") + this.settings.start_offset;
                if (!self.S(klass).hasClass("expanded")) {
                    if ($window.scrollTop() > distance) {
                        if (!self.S(klass).hasClass("fixed")) {
                            self.S(klass).addClass("fixed");
                            self.S("body").addClass("f-topbar-fixed");
                        }
                    } else if ($window.scrollTop() <= distance) {
                        if (self.S(klass).hasClass("fixed")) {
                            self.S(klass).removeClass("fixed");
                            self.S("body").removeClass("f-topbar-fixed");
                        }
                    }
                }
            }
        },
        off: function() {
            this.S(this.scope).off(".fndtn.topbar");
            this.S(window).off(".fndtn.topbar");
        },
        reflow: function() {}
    };
})(jQuery, window, window.document);

(function(CMS) {
    CMS.environment = {
        TOUCH_DOWN_EVENT_NAME: "mousedown touchstart",
        TOUCH_UP_EVENT_NAME: "mouseup touchend",
        TOUCH_MOVE_EVENT_NAME: "mousemove touchmove",
        TOUCH_DOUBLE_TAB_EVENT_NAME: "dblclick dbltap",
        isAndroid: function() {
            return navigator.userAgent.match(/Android/i);
        },
        isBlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        isIOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        isOpera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        isWindows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        isMobile: function() {
            if (CMS.environment.isAndroid() || CMS.environment.isBlackBerry() || CMS.environment.isIOS() || CMS.environment.isOpera() || CMS.environment.isWindows()) {
                return true;
            }
            return false;
        }
    };
})(window.CMS = window.CMS || {});

(function(CMS) {
    CMS.Supports = {
        touch: "ontouchstart" in document.documentElement || (window.DocumentTouch && document instanceof DocumentTouch ? true : false),
        touch2: "onorientationchange" in window && "ontouchstart" in window ? true : false,
        isAndroidNativeBrowser: function() {
            var ua = navigator.userAgent.toLowerCase();
            return ua.indexOf("android") != -1 && ua.indexOf("mobile") != -1 && ua.indexOf("chrome") == -1;
        }(),
        viewportW: function() {
            var a = document.documentElement.clientWidth, b = window.innerWidth;
            return a < b ? b : a;
        },
        viewportH: function() {
            var a = document.documentElement.clientHeight, b = window.innerHeight;
            return a < b ? b : a;
        }
    };
})(window.CMS = window.CMS || {});

(function(window) {
    if (!window.console) {
        console = {};
    }
    var funcs = "assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","), stub = function() {};
    for (var i = 0; i < funcs.length; i++) {
        if (!console[funcs[i]]) {
            console[funcs[i]] = stub;
        }
    }
})(window);

Function.prototype.debounce = function(milliseconds) {
    var baseFunction = this, timer = null, wait = milliseconds;
    return function() {
        var self = this, args = arguments;
        function complete() {
            baseFunction.apply(self, args);
            timer = null;
        }
        if (timer) {
            clearTimeout(timer);
        }
        timer = setTimeout(complete, wait);
    };
};

Function.prototype.throttle = function(milliseconds) {
    var baseFunction = this, lastEventTimestamp = null, limit = milliseconds;
    return function() {
        var self = this, args = arguments, now = Date.now();
        if (!lastEventTimestamp || now - lastEventTimestamp >= limit) {
            lastEventTimestamp = now;
            baseFunction.apply(self, args);
        }
    };
};

var notifications = window.notifications || {};

(function($, f) {
    "use strict";
    var events = {
        eventListeners: [],
        addListener: function(type, handler, destroyOnUse) {
            if (!events.listenerExists(type, handler)) {
                events.eventListeners.push({
                    destroyOnUse: destroyOnUse,
                    handler: handler,
                    type: type
                });
            }
        },
        listenerExists: function(type, handler) {
            var listener = {};
            for (var i = 0, n = events.eventListeners.length; i < n; i += 1) {
                listener = events.eventListeners[i];
                if (type === listener.type && handler === listener.handler) {
                    return true;
                }
            }
            return false;
        },
        removeListener: function(type, handler) {
            var listener = {};
            for (var i = 0, n = events.eventListeners.length; i < n; i += 1) {
                listener = events.eventListeners[i];
                if (type === listener.type && handler === listener.handler) {
                    events.eventListeners.splice(i, 1);
                    return;
                }
            }
        },
        sendNotification: function(type, params) {
            var listener = {};
            var handler;
            for (var i = events.eventListeners.length - 1; i >= 0; i -= 1) {
                listener = events.eventListeners[i];
                if (type === listener.type) {
                    handler = listener.handler;
                    if (listener.destroyOnUse) {
                        events.removeListener(listener.type, listener.handler);
                    }
                    handler(params);
                }
            }
        }
    };
    window.notifications = {
        sendNotification: function(type, params) {
            events.sendNotification(type, params);
        },
        addListener: function(type, handler, destroyOnUse) {
            if (destroyOnUse !== true) {
                destroyOnUse = false;
            }
            events.addListener(type, handler, destroyOnUse);
        },
        removeListener: function(type, handler) {
            events.removeListener(type, handler);
        },
        WINDOW_RESIZE: "WINDOW_RESIZE"
    };
})(window.CMS = window.CMS || {}, jQuery);

(function($, sr) {
    var debounce = function(func, threshold, execAsap) {
        var timeout;
        return function debounced() {
            var obj = this, args = arguments;
            function delayed() {
                if (!execAsap) {
                    func.apply(obj, args);
                }
                timeout = null;
            }
            if (timeout) {
                clearTimeout(timeout);
            } else if (execAsap) {
                func.apply(obj, args);
            }
            timeout = setTimeout(delayed, threshold || 100);
        };
    };
    jQuery.fn[sr] = function(fn) {
        return fn ? this.bind("resize", debounce(fn)) : this.trigger(sr);
    };
})(jQuery, "smartresize");

(function(win) {
    var doc = win.document;
    if (!win.navigator.standalone && !location.hash && win.addEventListener) {
        win.scrollTo(0, 1);
        var scrollTop = 1, getScrollTop = function() {
            return win.pageYOffset || doc.compatMode === "CSS1Compat" && doc.documentElement.scrollTop || doc.body.scrollTop || 0;
        }, bodycheck = setInterval(function() {
            if (doc.body) {
                clearInterval(bodycheck);
                scrollTop = getScrollTop();
                win.scrollTo(0, scrollTop === 1 ? 0 : 1);
            }
        }, 15);
        win.addEventListener("load", function() {
            setTimeout(function() {
                if (getScrollTop() < 20) {
                    win.scrollTo(0, scrollTop === 1 ? 0 : 1);
                }
            }, 0);
        }, false);
    }
})(this);

!function($) {
    function UTCDate() {
        return new Date(Date.UTC.apply(Date, arguments));
    }
    function UTCToday() {
        var today = new Date();
        return UTCDate(today.getUTCFullYear(), today.getUTCMonth(), today.getUTCDate());
    }
    var Datepicker = function(element, options) {
        var that = this;
        this.element = $(element);
        this.autoShow = options.autoShow || true;
        this.appendTo = options.appendTo || "body";
        this.closeButton = options.closeButton;
        this.language = options.language || this.element.data("date-language") || "en";
        this.language = this.language in dates ? this.language : this.language.split("-")[0];
        this.language = this.language in dates ? this.language : "en";
        this.isRTL = dates[this.language].rtl || false;
        this.format = DPGlobal.parseFormat(options.format || this.element.data("date-format") || dates[this.language].format || "mm/dd/yyyy");
        this.isInline = false;
        this.isInput = this.element.is("input");
        this.component = this.element.is(".date") ? this.element.find(".prefix, .postfix") : false;
        this.hasInput = this.component && this.element.find("input").length;
        this.disableDblClickSelection = options.disableDblClickSelection;
        this.onRender = options.onRender || function() {};
        if (this.component && this.component.length === 0) {
            this.component = false;
        }
        this.linkField = options.linkField || this.element.data("link-field") || false;
        this.linkFormat = DPGlobal.parseFormat(options.linkFormat || this.element.data("link-format") || "yyyy-mm-dd hh:ii:ss");
        this.minuteStep = options.minuteStep || this.element.data("minute-step") || 5;
        this.pickerPosition = options.pickerPosition || this.element.data("picker-position") || "bottom-right";
        this._attachEvents();
        this.minView = 0;
        if ("minView" in options) {
            this.minView = options.minView;
        } else if ("minView" in this.element.data()) {
            this.minView = this.element.data("min-view");
        }
        this.minView = DPGlobal.convertViewMode(this.minView);
        this.maxView = DPGlobal.modes.length - 1;
        if ("maxView" in options) {
            this.maxView = options.maxView;
        } else if ("maxView" in this.element.data()) {
            this.maxView = this.element.data("max-view");
        }
        this.maxView = DPGlobal.convertViewMode(this.maxView);
        this.startViewMode = "month";
        if ("startView" in options) {
            this.startViewMode = options.startView;
        } else if ("startView" in this.element.data()) {
            this.startViewMode = this.element.data("start-view");
        }
        this.startViewMode = DPGlobal.convertViewMode(this.startViewMode);
        this.viewMode = this.startViewMode;
        if (!("minView" in options) && !("maxView" in options) && !(this.element.data("min-view") && !this.element.data("max-view"))) {
            this.pickTime = false;
            if ("pickTime" in options) {
                this.pickTime = options.pickTime;
            }
            if (this.pickTime == true) {
                this.minView = 0;
                this.maxView = 4;
            } else {
                this.minView = 2;
                this.maxView = 4;
            }
        }
        this.forceParse = true;
        if ("forceParse" in options) {
            this.forceParse = options.forceParse;
        } else if ("dateForceParse" in this.element.data()) {
            this.forceParse = this.element.data("date-force-parse");
        }
        this.picker = $(DPGlobal.template).appendTo(this.isInline ? this.element : this.appendTo).on({
            click: $.proxy(this.click, this),
            mousedown: $.proxy(this.mousedown, this)
        });
        if (this.closeButton) {
            this.picker.find("a.datepicker-close").show();
        } else {
            this.picker.find("a.datepicker-close").hide();
        }
        if (this.isInline) {
            this.picker.addClass("datepicker-inline");
        } else {
            this.picker.addClass("datepicker-dropdown dropdown-menu");
        }
        if (this.isRTL) {
            this.picker.addClass("datepicker-rtl");
            this.picker.find(".prev i, .next i").toggleClass("fa fa-chevron-left fa-chevron-right").toggleClass("fa-chevron-left fa-chevron-right");
        }
        $(document).on("mousedown", function(e) {
            if ($(e.target).closest(".datepicker.datepicker-inline, .datepicker.datepicker-dropdown").length === 0) {
                that.hide();
            }
        });
        this.autoclose = true;
        if ("autoclose" in options) {
            this.autoclose = options.autoclose;
        } else if ("dateAutoclose" in this.element.data()) {
            this.autoclose = this.element.data("date-autoclose");
        }
        this.keyboardNavigation = true;
        if ("keyboardNavigation" in options) {
            this.keyboardNavigation = options.keyboardNavigation;
        } else if ("dateKeyboardNavigation" in this.element.data()) {
            this.keyboardNavigation = this.element.data("date-keyboard-navigation");
        }
        this.todayBtn = options.todayBtn || this.element.data("date-today-btn") || false;
        this.todayHighlight = options.todayHighlight || this.element.data("date-today-highlight") || false;
        this.calendarWeeks = false;
        if ("calendarWeeks" in options) {
            this.calendarWeeks = options.calendarWeeks;
        } else if ("dateCalendarWeeks" in this.element.data()) {
            this.calendarWeeks = this.element.data("date-calendar-weeks");
        }
        if (this.calendarWeeks) this.picker.find("tfoot th.today").attr("colspan", function(i, val) {
            return parseInt(val) + 1;
        });
        this.weekStart = (options.weekStart || this.element.data("date-weekstart") || dates[this.language].weekStart || 0) % 7;
        this.weekEnd = (this.weekStart + 6) % 7;
        this.startDate = -Infinity;
        this.endDate = Infinity;
        this.daysOfWeekDisabled = [];
        this.setStartDate(options.startDate || this.element.data("date-startdate"));
        this.setEndDate(options.endDate || this.element.data("date-enddate"));
        this.setDaysOfWeekDisabled(options.daysOfWeekDisabled || this.element.data("date-days-of-week-disabled"));
        this.fillDow();
        this.fillMonths();
        this.update();
        this.showMode();
        if (this.isInline) {
            this.show();
        }
    };
    Datepicker.prototype = {
        constructor: Datepicker,
        _events: [],
        _attachEvents: function() {
            this._detachEvents();
            if (this.isInput) {
                this._events = [ [ this.element, {
                    focus: this.autoShow ? $.proxy(this.show, this) : function() {},
                    keyup: $.proxy(this.update, this),
                    keydown: $.proxy(this.keydown, this)
                } ] ];
            } else if (this.component && this.hasInput) {
                this._events = [ [ this.element.find("input"), {
                    focus: this.autoShow ? $.proxy(this.show, this) : function() {},
                    keyup: $.proxy(this.update, this),
                    keydown: $.proxy(this.keydown, this)
                } ], [ this.component, {
                    click: $.proxy(this.show, this)
                } ] ];
            } else if (this.element.is("div")) {
                this.isInline = true;
            } else {
                this._events = [ [ this.element, {
                    click: $.proxy(this.show, this)
                } ] ];
            }
            if (this.disableDblClickSelection) {
                this._events[this._events.length] = [ this.element, {
                    dblclick: function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        $(this).blur();
                    }
                } ];
            }
            for (var i = 0, el, ev; i < this._events.length; i++) {
                el = this._events[i][0];
                ev = this._events[i][1];
                el.on(ev);
            }
        },
        _detachEvents: function() {
            for (var i = 0, el, ev; i < this._events.length; i++) {
                el = this._events[i][0];
                ev = this._events[i][1];
                el.off(ev);
            }
            this._events = [];
        },
        show: function(e) {
            this.picker.show();
            this.height = this.component ? this.component.outerHeight() : this.element.outerHeight();
            this.update();
            this.place();
            $(window).on("resize", $.proxy(this.place, this));
            if (e) {
                e.stopPropagation();
                e.preventDefault();
            }
            this.element.trigger({
                type: "show",
                date: this.date
            });
        },
        hide: function(e) {
            if (this.isInline) return;
            if (!this.picker.is(":visible")) return;
            this.picker.hide();
            $(window).off("resize", this.place);
            this.viewMode = this.startViewMode;
            this.showMode();
            if (!this.isInput) {
                $(document).off("mousedown", this.hide);
            }
            if (this.forceParse && (this.isInput && this.element.val() || this.hasInput && this.element.find("input").val())) this.setValue();
            this.element.trigger({
                type: "hide",
                date: this.date
            });
        },
        remove: function() {
            this._detachEvents();
            this.picker.remove();
            delete this.element.data().datepicker;
        },
        getDate: function() {
            var d = this.getUTCDate();
            return new Date(d.getTime() + d.getTimezoneOffset() * 6e4);
        },
        getUTCDate: function() {
            return this.date;
        },
        setDate: function(d) {
            this.setUTCDate(new Date(d.getTime() - d.getTimezoneOffset() * 6e4));
        },
        setUTCDate: function(d) {
            this.date = d;
            this.setValue();
        },
        setValue: function() {
            var formatted = this.getFormattedDate();
            if (!this.isInput) {
                if (this.component) {
                    this.element.find("input").val(formatted);
                }
                this.element.data("date", formatted);
            } else {
                this.element.val(formatted);
            }
        },
        getFormattedDate: function(format) {
            if (format === undefined) format = this.format;
            return DPGlobal.formatDate(this.date, format, this.language);
        },
        setStartDate: function(startDate) {
            this.startDate = startDate || -Infinity;
            if (this.startDate !== -Infinity) {
                this.startDate = DPGlobal.parseDate(this.startDate, this.format, this.language);
            }
            this.update();
            this.updateNavArrows();
        },
        setEndDate: function(endDate) {
            this.endDate = endDate || Infinity;
            if (this.endDate !== Infinity) {
                this.endDate = DPGlobal.parseDate(this.endDate, this.format, this.language);
            }
            this.update();
            this.updateNavArrows();
        },
        setDaysOfWeekDisabled: function(daysOfWeekDisabled) {
            this.daysOfWeekDisabled = daysOfWeekDisabled || [];
            if (!$.isArray(this.daysOfWeekDisabled)) {
                this.daysOfWeekDisabled = this.daysOfWeekDisabled.split(/,\s*/);
            }
            this.daysOfWeekDisabled = $.map(this.daysOfWeekDisabled, function(d) {
                return parseInt(d, 10);
            });
            this.update();
            this.updateNavArrows();
        },
        place: function() {
            if (this.isInline) return;
            var zIndex = parseInt(this.element.parents().filter(function() {
                return $(this).css("z-index") != "auto";
            }).first().css("z-index")) + 10;
            var textbox = this.component ? this.component : this.element;
            var offset = textbox.offset();
            var height = textbox.outerHeight() + parseInt(textbox.css("margin-top"));
            var width = textbox.outerWidth() + parseInt(textbox.css("margin-left"));
            var fullOffsetTop = offset.top + height;
            var offsetLeft = offset.left;
            if (fullOffsetTop + this.picker.outerHeight() >= $(window).scrollTop() + $(window).height()) {
                fullOffsetTop = offset.top - this.picker.outerHeight();
            }
            if (offset.left + this.picker.width() >= $(window).width()) {
                offsetLeft = offset.left + width - this.picker.width();
            }
            this.picker.css({
                top: fullOffsetTop,
                left: offsetLeft,
                zIndex: zIndex
            });
        },
        update: function() {
            var date, fromArgs = false;
            if (arguments && arguments.length && (typeof arguments[0] === "string" || arguments[0] instanceof Date)) {
                date = arguments[0];
                fromArgs = true;
            } else {
                date = this.isInput ? this.element.val() : this.element.data("date") || this.element.find("input").val();
            }
            this.date = DPGlobal.parseDate(date, this.format, this.language);
            if (fromArgs) this.setValue();
            if (this.date < this.startDate) {
                this.viewDate = new Date(this.startDate.valueOf());
            } else if (this.date > this.endDate) {
                this.viewDate = new Date(this.endDate.valueOf());
            } else {
                this.viewDate = new Date(this.date.valueOf());
            }
            this.fill();
        },
        fillDow: function() {
            var dowCnt = this.weekStart, html = "<tr>";
            if (this.calendarWeeks) {
                var cell = '<th class="cw">&nbsp;</th>';
                html += cell;
                this.picker.find(".datepicker-days thead tr:first-child").prepend(cell);
            }
            while (dowCnt < this.weekStart + 7) {
                html += '<th class="dow">' + dates[this.language].daysMin[dowCnt++ % 7] + "</th>";
            }
            html += "</tr>";
            this.picker.find(".datepicker-days thead").append(html);
        },
        fillMonths: function() {
            var html = "", i = 0;
            while (i < 12) {
                html += '<span class="month">' + dates[this.language].monthsShort[i++] + "</span>";
            }
            this.picker.find(".datepicker-months td").html(html);
        },
        fill: function() {
            if (this.date == null || this.viewDate == null) {
                return;
            }
            var d = new Date(this.viewDate.valueOf()), year = d.getUTCFullYear(), month = d.getUTCMonth(), dayMonth = d.getUTCDate(), hours = d.getUTCHours(), minutes = d.getUTCMinutes(), startYear = this.startDate !== -Infinity ? this.startDate.getUTCFullYear() : -Infinity, startMonth = this.startDate !== -Infinity ? this.startDate.getUTCMonth() : -Infinity, endYear = this.endDate !== Infinity ? this.endDate.getUTCFullYear() : Infinity, endMonth = this.endDate !== Infinity ? this.endDate.getUTCMonth() : Infinity, currentDate = this.date && this.date.valueOf(), today = new Date(), titleFormat = dates[this.language].titleFormat || dates["en"].titleFormat;
            this.picker.find(".datepicker-days thead th:eq(1)").text(dates[this.language].months[month] + " " + year);
            this.picker.find(".datepicker-hours thead th:eq(1)").text(dayMonth + " " + dates[this.language].months[month] + " " + year);
            this.picker.find(".datepicker-minutes thead th:eq(1)").text(dayMonth + " " + dates[this.language].months[month] + " " + year);
            this.picker.find("tfoot th.today").text(dates[this.language].today).toggle(this.todayBtn !== false);
            this.updateNavArrows();
            this.fillMonths();
            var prevMonth = UTCDate(year, month - 1, 28, 0, 0, 0, 0), day = DPGlobal.getDaysInMonth(prevMonth.getUTCFullYear(), prevMonth.getUTCMonth());
            prevMonth.setUTCDate(day);
            prevMonth.setUTCDate(day - (prevMonth.getUTCDay() - this.weekStart + 7) % 7);
            var nextMonth = new Date(prevMonth.valueOf());
            nextMonth.setUTCDate(nextMonth.getUTCDate() + 42);
            nextMonth = nextMonth.valueOf();
            var html = [];
            var clsName;
            while (prevMonth.valueOf() < nextMonth) {
                if (prevMonth.getUTCDay() == this.weekStart) {
                    html.push("<tr>");
                    if (this.calendarWeeks) {
                        var a = new Date(prevMonth.getUTCFullYear(), prevMonth.getUTCMonth(), prevMonth.getUTCDate() - prevMonth.getDay() + 10 - (this.weekStart && this.weekStart % 7 < 5 && 7)), b = new Date(a.getFullYear(), 0, 4), calWeek = ~~((a - b) / 864e5 / 7 + 1.5);
                        html.push('<td class="cw">' + calWeek + "</td>");
                    }
                }
                clsName = " " + this.onRender(prevMonth) + " ";
                if (prevMonth.getUTCFullYear() < year || prevMonth.getUTCFullYear() == year && prevMonth.getUTCMonth() < month) {
                    clsName += " old";
                } else if (prevMonth.getUTCFullYear() > year || prevMonth.getUTCFullYear() == year && prevMonth.getUTCMonth() > month) {
                    clsName += " new";
                }
                if (this.todayHighlight && prevMonth.getUTCFullYear() == today.getFullYear() && prevMonth.getUTCMonth() == today.getMonth() && prevMonth.getUTCDate() == today.getDate()) {
                    clsName += " today";
                }
                if (currentDate && prevMonth.valueOf() == currentDate) {
                    clsName += " active";
                }
                if (prevMonth.valueOf() < this.startDate || prevMonth.valueOf() > this.endDate || $.inArray(prevMonth.getUTCDay(), this.daysOfWeekDisabled) !== -1) {
                    clsName += " disabled";
                }
                html.push('<td class="day' + clsName + '">' + prevMonth.getUTCDate() + "</td>");
                if (prevMonth.getUTCDay() == this.weekEnd) {
                    html.push("</tr>");
                }
                prevMonth.setUTCDate(prevMonth.getUTCDate() + 1);
            }
            this.picker.find(".datepicker-days tbody").empty().append(html.join(""));
            html = [];
            for (var i = 0; i < 24; i++) {
                var actual = UTCDate(year, month, dayMonth, i);
                clsName = "";
                if (actual.valueOf() + 36e5 < this.startDate || actual.valueOf() > this.endDate) {
                    clsName += " disabled";
                } else if (hours == i) {
                    clsName += " active";
                }
                html.push('<span class="hour' + clsName + '">' + i + ":00</span>");
            }
            this.picker.find(".datepicker-hours td").html(html.join(""));
            html = [];
            for (var i = 0; i < 60; i += this.minuteStep) {
                var actual = UTCDate(year, month, dayMonth, hours, i);
                clsName = "";
                if (actual.valueOf() < this.startDate || actual.valueOf() > this.endDate) {
                    clsName += " disabled";
                } else if (Math.floor(minutes / this.minuteStep) == Math.floor(i / this.minuteStep)) {
                    clsName += " active";
                }
                html.push('<span class="minute' + clsName + '">' + hours + ":" + (i < 10 ? "0" + i : i) + "</span>");
            }
            this.picker.find(".datepicker-minutes td").html(html.join(""));
            var currentYear = this.date && this.date.getUTCFullYear();
            var months = this.picker.find(".datepicker-months").find("th:eq(1)").text(year).end().find("span").removeClass("active");
            if (currentYear && currentYear == year) {
                months.eq(this.date.getUTCMonth()).addClass("active");
            }
            if (year < startYear || year > endYear) {
                months.addClass("disabled");
            }
            if (year == startYear) {
                months.slice(0, startMonth).addClass("disabled");
            }
            if (year == endYear) {
                months.slice(endMonth + 1).addClass("disabled");
            }
            html = "";
            year = parseInt(year / 10, 10) * 10;
            var yearCont = this.picker.find(".datepicker-years").find("th:eq(1)").text(year + "-" + (year + 9)).end().find("td");
            year -= 1;
            for (var i = -1; i < 11; i++) {
                html += '<span class="year' + (i == -1 || i == 10 ? " old" : "") + (currentYear == year ? " active" : "") + (year < startYear || year > endYear ? " disabled" : "") + '">' + year + "</span>";
                year += 1;
            }
            yearCont.html(html);
        },
        updateNavArrows: function() {
            var d = new Date(this.viewDate), year = d.getUTCFullYear(), month = d.getUTCMonth(), day = d.getUTCDate(), hour = d.getUTCHours();
            switch (this.viewMode) {
              case 0:
                if (this.startDate !== -Infinity && year <= this.startDate.getUTCFullYear() && month <= this.startDate.getUTCMonth() && day <= this.startDate.getUTCDate() && hour <= this.startDate.getUTCHours()) {
                    this.picker.find(".prev").css({
                        visibility: "hidden"
                    });
                } else {
                    this.picker.find(".prev").css({
                        visibility: "visible"
                    });
                }
                if (this.endDate !== Infinity && year >= this.endDate.getUTCFullYear() && month >= this.endDate.getUTCMonth() && day >= this.endDate.getUTCDate() && hour >= this.endDate.getUTCHours()) {
                    this.picker.find(".next").css({
                        visibility: "hidden"
                    });
                } else {
                    this.picker.find(".next").css({
                        visibility: "visible"
                    });
                }
                break;

              case 1:
                if (this.startDate !== -Infinity && year <= this.startDate.getUTCFullYear() && month <= this.startDate.getUTCMonth() && day <= this.startDate.getUTCDate()) {
                    this.picker.find(".prev").css({
                        visibility: "hidden"
                    });
                } else {
                    this.picker.find(".prev").css({
                        visibility: "visible"
                    });
                }
                if (this.endDate !== Infinity && year >= this.endDate.getUTCFullYear() && month >= this.endDate.getUTCMonth() && day >= this.endDate.getUTCDate()) {
                    this.picker.find(".next").css({
                        visibility: "hidden"
                    });
                } else {
                    this.picker.find(".next").css({
                        visibility: "visible"
                    });
                }
                break;

              case 2:
                if (this.startDate !== -Infinity && year <= this.startDate.getUTCFullYear() && month <= this.startDate.getUTCMonth()) {
                    this.picker.find(".prev").css({
                        visibility: "hidden"
                    });
                } else {
                    this.picker.find(".prev").css({
                        visibility: "visible"
                    });
                }
                if (this.endDate !== Infinity && year >= this.endDate.getUTCFullYear() && month >= this.endDate.getUTCMonth()) {
                    this.picker.find(".next").css({
                        visibility: "hidden"
                    });
                } else {
                    this.picker.find(".next").css({
                        visibility: "visible"
                    });
                }
                break;

              case 3:
              case 4:
                if (this.startDate !== -Infinity && year <= this.startDate.getUTCFullYear()) {
                    this.picker.find(".prev").css({
                        visibility: "hidden"
                    });
                } else {
                    this.picker.find(".prev").css({
                        visibility: "visible"
                    });
                }
                if (this.endDate !== Infinity && year >= this.endDate.getUTCFullYear()) {
                    this.picker.find(".next").css({
                        visibility: "hidden"
                    });
                } else {
                    this.picker.find(".next").css({
                        visibility: "visible"
                    });
                }
                break;
            }
        },
        click: function(e) {
            e.stopPropagation();
            e.preventDefault();
            if ($(e.target).hasClass("datepicker-close") || $(e.target).parent().hasClass("datepicker-close")) {
                this.hide();
            }
            var target = $(e.target).closest("span, td, th");
            if (target.length == 1) {
                if (target.is(".disabled")) {
                    this.element.trigger({
                        type: "outOfRange",
                        date: this.viewDate,
                        startDate: this.startDate,
                        endDate: this.endDate
                    });
                    return;
                }
                switch (target[0].nodeName.toLowerCase()) {
                  case "th":
                    switch (target[0].className) {
                      case "date-switch":
                        this.showMode(1);
                        break;

                      case "prev":
                      case "next":
                        var dir = DPGlobal.modes[this.viewMode].navStep * (target[0].className == "prev" ? -1 : 1);
                        switch (this.viewMode) {
                          case 0:
                            this.viewDate = this.moveHour(this.viewDate, dir);
                            break;

                          case 1:
                            this.viewDate = this.moveDate(this.viewDate, dir);
                            break;

                          case 2:
                            this.viewDate = this.moveMonth(this.viewDate, dir);
                            break;

                          case 3:
                          case 4:
                            this.viewDate = this.moveYear(this.viewDate, dir);
                            break;
                        }
                        this.fill();
                        break;

                      case "today":
                        var date = new Date();
                        date = UTCDate(date.getFullYear(), date.getMonth(), date.getDate(), date.getHours(), date.getMinutes(), date.getSeconds());
                        this.viewMode = this.startViewMode;
                        this.showMode(0);
                        this._setDate(date);
                        break;
                    }
                    break;

                  case "span":
                    if (!target.is(".disabled")) {
                        if (target.is(".month")) {
                            this.viewDate.setUTCDate(1);
                            var month = target.parent().find("span").index(target);
                            this.viewDate.setUTCMonth(month);
                            this.element.trigger({
                                type: "changeMonth",
                                date: this.viewDate
                            });
                        } else if (target.is(".year")) {
                            this.viewDate.setUTCDate(1);
                            var year = parseInt(target.text(), 10) || 0;
                            this.viewDate.setUTCFullYear(year);
                            this.element.trigger({
                                type: "changeYear",
                                date: this.viewDate
                            });
                        } else if (target.is(".hour")) {
                            var hours = parseInt(target.text(), 10) || 0;
                            var year = this.viewDate.getUTCFullYear(), month = this.viewDate.getUTCMonth(), day = this.viewDate.getUTCDate(), minutes = this.viewDate.getUTCMinutes(), seconds = this.viewDate.getUTCSeconds();
                            this._setDate(UTCDate(year, month, day, hours, minutes, seconds, 0));
                        } else if (target.is(".minute")) {
                            var minutes = parseInt(target.text().substr(target.text().indexOf(":") + 1), 10) || 0;
                            var year = this.viewDate.getUTCFullYear(), month = this.viewDate.getUTCMonth(), day = this.viewDate.getUTCDate(), hours = this.viewDate.getUTCHours(), seconds = this.viewDate.getUTCSeconds();
                            this._setDate(UTCDate(year, month, day, hours, minutes, seconds, 0));
                        }
                        if (this.viewMode != 0) {
                            var oldViewMode = this.viewMode;
                            this.showMode(-1);
                            this.fill();
                            if (oldViewMode == this.viewMode && this.autoclose) {
                                this.hide();
                            }
                        } else {
                            this.fill();
                            if (this.autoclose) {
                                this.hide();
                            }
                        }
                    }
                    break;

                  case "td":
                    if (target.is(".day") && !target.is(".disabled")) {
                        var day = parseInt(target.text(), 10) || 1;
                        var year = this.viewDate.getUTCFullYear(), month = this.viewDate.getUTCMonth(), hours = this.viewDate.getUTCHours(), minutes = this.viewDate.getUTCMinutes(), seconds = this.viewDate.getUTCSeconds();
                        if (target.is(".old")) {
                            if (month === 0) {
                                month = 11;
                                year -= 1;
                            } else {
                                month -= 1;
                            }
                        } else if (target.is(".new")) {
                            if (month == 11) {
                                month = 0;
                                year += 1;
                            } else {
                                month += 1;
                            }
                        }
                        this._setDate(UTCDate(year, month, day, hours, minutes, seconds, 0));
                    }
                    var oldViewMode = this.viewMode;
                    this.showMode(-1);
                    this.fill();
                    if (oldViewMode == this.viewMode && this.autoclose) {
                        this.hide();
                    }
                    break;
                }
            }
        },
        _setDate: function(date, which) {
            if (!which || which == "date") this.date = date;
            if (!which || which == "view") this.viewDate = date;
            this.fill();
            this.setValue();
            this.element.trigger({
                type: "changeDate",
                date: this.date
            });
            var element;
            if (this.isInput) {
                element = this.element;
            } else if (this.component) {
                element = this.element.find("input");
            }
            if (element) {
                element.change();
                if (this.autoclose && (!which || which == "date")) {}
            }
        },
        moveHour: function(date, dir) {
            if (!dir) return date;
            var new_date = new Date(date.valueOf());
            dir = dir > 0 ? 1 : -1;
            new_date.setUTCHours(new_date.getUTCHours() + dir);
            return new_date;
        },
        moveDate: function(date, dir) {
            if (!dir) return date;
            var new_date = new Date(date.valueOf());
            dir = dir > 0 ? 1 : -1;
            new_date.setUTCDate(new_date.getUTCDate() + dir);
            return new_date;
        },
        moveMonth: function(date, dir) {
            if (!dir) return date;
            var new_date = new Date(date.valueOf()), day = new_date.getUTCDate(), month = new_date.getUTCMonth(), mag = Math.abs(dir), new_month, test;
            dir = dir > 0 ? 1 : -1;
            if (mag == 1) {
                test = dir == -1 ? function() {
                    return new_date.getUTCMonth() == month;
                } : function() {
                    return new_date.getUTCMonth() != new_month;
                };
                new_month = month + dir;
                new_date.setUTCMonth(new_month);
                if (new_month < 0 || new_month > 11) new_month = (new_month + 12) % 12;
            } else {
                for (var i = 0; i < mag; i++) new_date = this.moveMonth(new_date, dir);
                new_month = new_date.getUTCMonth();
                new_date.setUTCDate(day);
                test = function() {
                    return new_month != new_date.getUTCMonth();
                };
            }
            while (test()) {
                new_date.setUTCDate(--day);
                new_date.setUTCMonth(new_month);
            }
            return new_date;
        },
        moveYear: function(date, dir) {
            return this.moveMonth(date, dir * 12);
        },
        dateWithinRange: function(date) {
            return date >= this.startDate && date <= this.endDate;
        },
        keydown: function(e) {
            if (this.picker.is(":not(:visible)")) {
                if (e.keyCode == 27) this.show();
                return;
            }
            var dateChanged = false, dir, day, month, newDate, newViewDate;
            switch (e.keyCode) {
              case 27:
                this.hide();
                e.preventDefault();
                break;

              case 37:
              case 39:
                if (!this.keyboardNavigation) break;
                dir = e.keyCode == 37 ? -1 : 1;
                if (e.ctrlKey) {
                    newDate = this.moveYear(this.date, dir);
                    newViewDate = this.moveYear(this.viewDate, dir);
                } else if (e.shiftKey) {
                    newDate = this.moveMonth(this.date, dir);
                    newViewDate = this.moveMonth(this.viewDate, dir);
                } else {
                    newDate = new Date(this.date.valueOf());
                    newDate.setUTCDate(this.date.getUTCDate() + dir);
                    newViewDate = new Date(this.viewDate.valueOf());
                    newViewDate.setUTCDate(this.viewDate.getUTCDate() + dir);
                }
                if (this.dateWithinRange(newDate)) {
                    this.date = newDate;
                    this.viewDate = newViewDate;
                    this.setValue();
                    this.update();
                    e.preventDefault();
                    dateChanged = true;
                }
                break;

              case 38:
              case 40:
                if (!this.keyboardNavigation) break;
                dir = e.keyCode == 38 ? -1 : 1;
                if (e.ctrlKey) {
                    newDate = this.moveYear(this.date, dir);
                    newViewDate = this.moveYear(this.viewDate, dir);
                } else if (e.shiftKey) {
                    newDate = this.moveMonth(this.date, dir);
                    newViewDate = this.moveMonth(this.viewDate, dir);
                } else {
                    newDate = new Date(this.date.valueOf());
                    newDate.setUTCDate(this.date.getUTCDate() + dir * 7);
                    newViewDate = new Date(this.viewDate.valueOf());
                    newViewDate.setUTCDate(this.viewDate.getUTCDate() + dir * 7);
                }
                if (this.dateWithinRange(newDate)) {
                    this.date = newDate;
                    this.viewDate = newViewDate;
                    this.setValue();
                    this.update();
                    e.preventDefault();
                    dateChanged = true;
                }
                break;

              case 13:
                this.hide();
                e.preventDefault();
                break;

              case 9:
                this.hide();
                break;
            }
            if (dateChanged) {
                this.element.trigger({
                    type: "changeDate",
                    date: this.date
                });
                var element;
                if (this.isInput) {
                    element = this.element;
                } else if (this.component) {
                    element = this.element.find("input");
                }
                if (element) {
                    element.change();
                }
            }
        },
        showMode: function(dir) {
            if (dir) {
                var newViewMode = Math.max(0, Math.min(DPGlobal.modes.length - 1, this.viewMode + dir));
                if (newViewMode >= this.minView && newViewMode <= this.maxView) {
                    this.viewMode = newViewMode;
                }
            }
            this.picker.find(">div").hide().filter(".datepicker-" + DPGlobal.modes[this.viewMode].clsName).css("display", "block");
            this.updateNavArrows();
        },
        reset: function(e) {
            this._setDate(null, "date");
        }
    };
    $.fn.fdatepicker = function(option) {
        var args = Array.apply(null, arguments);
        args.shift();
        return this.each(function() {
            var $this = $(this), data = $this.data("datepicker"), options = typeof option == "object" && option;
            if (!data) {
                $this.data("datepicker", data = new Datepicker(this, $.extend({}, $.fn.fdatepicker.defaults, options)));
            }
            if (typeof option == "string" && typeof data[option] == "function") {
                data[option].apply(data, args);
            }
        });
    };
    $.fn.fdatepicker.defaults = {
        onRender: function(date) {
            return "";
        }
    };
    $.fn.fdatepicker.Constructor = Datepicker;
    var dates = $.fn.fdatepicker.dates = {
        en: {
            days: [ "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday" ],
            daysShort: [ "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun" ],
            daysMin: [ "Su", "Mo", "Tu", "We", "Th", "Fr", "Sa", "Su" ],
            months: [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ],
            monthsShort: [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ],
            today: "Today",
            titleFormat: "MM yyyy"
        }
    };
    var DPGlobal = {
        modes: [ {
            clsName: "minutes",
            navFnc: "Hours",
            navStep: 1
        }, {
            clsName: "hours",
            navFnc: "Date",
            navStep: 1
        }, {
            clsName: "days",
            navFnc: "Month",
            navStep: 1
        }, {
            clsName: "months",
            navFnc: "FullYear",
            navStep: 1
        }, {
            clsName: "years",
            navFnc: "FullYear",
            navStep: 10
        } ],
        isLeapYear: function(year) {
            return year % 4 === 0 && year % 100 !== 0 || year % 400 === 0;
        },
        getDaysInMonth: function(year, month) {
            return [ 31, DPGlobal.isLeapYear(year) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ][month];
        },
        validParts: /hh?|ii?|ss?|dd?|mm?|MM?|yy(?:yy)?/g,
        nonpunctuation: /[^ -\/:-@\[\u3400-\u9fff-`{-~\t\n\r]+/g,
        parseFormat: function(format) {
            var separators = format.replace(this.validParts, "\x00").split("\x00"), parts = format.match(this.validParts);
            if (!separators || !separators.length || !parts || parts.length === 0) {
                throw new Error("Invalid date format.");
            }
            return {
                separators: separators,
                parts: parts
            };
        },
        parseDate: function(date, format, language) {
            if (date instanceof Date) return new Date(date.valueOf() - date.getTimezoneOffset() * 6e4);
            if (/^\d{4}\-\d{1,2}\-\d{1,2}$/.test(date)) {
                format = this.parseFormat("yyyy-mm-dd");
            }
            if (/^\d{4}\-\d{1,2}\-\d{1,2}[T ]\d{1,2}\:\d{1,2}$/.test(date)) {
                format = this.parseFormat("yyyy-mm-dd hh:ii");
            }
            if (/^\d{4}\-\d{1,2}\-\d{1,2}[T ]\d{1,2}\:\d{1,2}\:\d{1,2}[Z]{0,1}$/.test(date)) {
                format = this.parseFormat("yyyy-mm-dd hh:ii:ss");
            }
            if (/^[-+]\d+[dmwy]([\s,]+[-+]\d+[dmwy])*$/.test(date)) {
                var part_re = /([-+]\d+)([dmwy])/, parts = date.match(/([-+]\d+)([dmwy])/g), part, dir;
                date = new Date();
                for (var i = 0; i < parts.length; i++) {
                    part = part_re.exec(parts[i]);
                    dir = parseInt(part[1]);
                    switch (part[2]) {
                      case "d":
                        date.setUTCDate(date.getUTCDate() + dir);
                        break;

                      case "m":
                        date = Datetimepicker.prototype.moveMonth.call(Datetimepicker.prototype, date, dir);
                        break;

                      case "w":
                        date.setUTCDate(date.getUTCDate() + dir * 7);
                        break;

                      case "y":
                        date = Datetimepicker.prototype.moveYear.call(Datetimepicker.prototype, date, dir);
                        break;
                    }
                }
                return UTCDate(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate(), date.getUTCHours(), date.getUTCMinutes(), date.getUTCSeconds());
            }
            var parts = date && date.match(this.nonpunctuation) || [], date = new Date(), parsed = {}, setters_order = [ "hh", "h", "ii", "i", "ss", "s", "yyyy", "yy", "M", "MM", "m", "mm", "d", "dd" ], setters_map = {
                hh: function(d, v) {
                    return d.setUTCHours(v);
                },
                h: function(d, v) {
                    return d.setUTCHours(v);
                },
                ii: function(d, v) {
                    return d.setUTCMinutes(v);
                },
                i: function(d, v) {
                    return d.setUTCMinutes(v);
                },
                ss: function(d, v) {
                    return d.setUTCSeconds(v);
                },
                s: function(d, v) {
                    return d.setUTCSeconds(v);
                },
                yyyy: function(d, v) {
                    return d.setUTCFullYear(v);
                },
                yy: function(d, v) {
                    return d.setUTCFullYear(2e3 + v);
                },
                m: function(d, v) {
                    v -= 1;
                    while (v < 0) v += 12;
                    v %= 12;
                    d.setUTCMonth(v);
                    while (d.getUTCMonth() != v) d.setUTCDate(d.getUTCDate() - 1);
                    return d;
                },
                d: function(d, v) {
                    return d.setUTCDate(v);
                }
            }, val, filtered, part;
            setters_map["M"] = setters_map["MM"] = setters_map["mm"] = setters_map["m"];
            setters_map["dd"] = setters_map["d"];
            date = UTCDate(date.getFullYear(), date.getMonth(), date.getDate(), 0, 0, 0);
            if (parts.length == format.parts.length) {
                for (var i = 0, cnt = format.parts.length; i < cnt; i++) {
                    val = parseInt(parts[i], 10);
                    part = format.parts[i];
                    if (isNaN(val)) {
                        switch (part) {
                          case "MM":
                            filtered = $(dates[language].months).filter(function() {
                                var m = this.slice(0, parts[i].length), p = parts[i].slice(0, m.length);
                                return m == p;
                            });
                            val = $.inArray(filtered[0], dates[language].months) + 1;
                            break;

                          case "M":
                            filtered = $(dates[language].monthsShort).filter(function() {
                                var m = this.slice(0, parts[i].length), p = parts[i].slice(0, m.length);
                                return m == p;
                            });
                            val = $.inArray(filtered[0], dates[language].monthsShort) + 1;
                            break;
                        }
                    }
                    parsed[part] = val;
                }
                for (var i = 0, s; i < setters_order.length; i++) {
                    s = setters_order[i];
                    if (s in parsed && !isNaN(parsed[s])) setters_map[s](date, parsed[s]);
                }
            }
            return date;
        },
        formatDate: function(date, format, language) {
            if (date == null) {
                return "";
            }
            var val = {
                h: date.getUTCHours(),
                i: date.getUTCMinutes(),
                s: date.getUTCSeconds(),
                d: date.getUTCDate(),
                m: date.getUTCMonth() + 1,
                M: dates[language].monthsShort[date.getUTCMonth()],
                MM: dates[language].months[date.getUTCMonth()],
                yy: date.getUTCFullYear().toString().substring(2),
                yyyy: date.getUTCFullYear()
            };
            val.hh = (val.h < 10 ? "0" : "") + val.h;
            val.ii = (val.i < 10 ? "0" : "") + val.i;
            val.ss = (val.s < 10 ? "0" : "") + val.s;
            val.dd = (val.d < 10 ? "0" : "") + val.d;
            val.mm = (val.m < 10 ? "0" : "") + val.m;
            var date = [], seps = $.extend([], format.separators);
            for (var i = 0, cnt = format.parts.length; i < cnt; i++) {
                if (seps.length) date.push(seps.shift());
                date.push(val[format.parts[i]]);
            }
            return date.join("");
        },
        convertViewMode: function(viewMode) {
            switch (viewMode) {
              case 4:
              case "decade":
                viewMode = 4;
                break;

              case 3:
              case "year":
                viewMode = 3;
                break;

              case 2:
              case "month":
                viewMode = 2;
                break;

              case 1:
              case "day":
                viewMode = 1;
                break;

              case 0:
              case "hour":
                viewMode = 0;
                break;
            }
            return viewMode;
        },
        headTemplate: "<thead>" + "<tr>" + '<th class="prev"><i class="fa fa-chevron-left fi-arrow-left"/></th>' + '<th colspan="5" class="date-switch"></th>' + '<th class="next"><i class="fa fa-chevron-right fi-arrow-right"/></th>' + "</tr>" + "</thead>",
        contTemplate: '<tbody><tr><td colspan="7"></td></tr></tbody>',
        footTemplate: '<tfoot><tr><th colspan="7" class="today"></th></tr></tfoot>'
    };
    DPGlobal.template = '<div class="datepicker">' + '<div class="datepicker-minutes">' + '<table class=" table-condensed">' + DPGlobal.headTemplate + DPGlobal.contTemplate + DPGlobal.footTemplate + "</table>" + "</div>" + '<div class="datepicker-hours">' + '<table class=" table-condensed">' + DPGlobal.headTemplate + DPGlobal.contTemplate + DPGlobal.footTemplate + "</table>" + "</div>" + '<div class="datepicker-days">' + '<table class=" table-condensed">' + DPGlobal.headTemplate + "<tbody></tbody>" + DPGlobal.footTemplate + "</table>" + "</div>" + '<div class="datepicker-months">' + '<table class="table-condensed">' + DPGlobal.headTemplate + DPGlobal.contTemplate + DPGlobal.footTemplate + "</table>" + "</div>" + '<div class="datepicker-years">' + '<table class="table-condensed">' + DPGlobal.headTemplate + DPGlobal.contTemplate + DPGlobal.footTemplate + "</table>" + "</div>" + '<a class="button datepicker-close tiny alert right" style="width:auto;"><i class="fa fa-remove fa-times fi-x"></i></a>' + "</div>";
    $.fn.fdatepicker.DPGlobal = DPGlobal;
}(window.jQuery);

(function(CMS, $, window, document, undefined) {
    "use strict";
    $(function() {
        CMS.Config.init();
    });
    CMS.Config = {
        $body: $(document.body),
        init: function() {
            CMS.foundationConfig.init();
            CMS.UI.init();
            CMS.windowResize.init();
            if (CMS.Supports.touch) {
                CMS.touch.init();
            }
            if (CMS.environment.isMobile()) {
                CMS.mobileSpecific.init();
            }
            $(window).load(function() {});
        }
    };
    CMS.foundationConfig = {
        init: function() {
            $(document).foundation({
                reveal: {
                    animation: "fadeAndPop",
                    animation_speed: 350,
                    close_on_background_click: true,
                    dismiss_modal_class: "close-reveal-modal",
                    bg_class: "reveal-modal-bg"
                },
                orbit: {
                    animation: "fade",
                    timer_speed: 8e3,
                    pause_on_hover: true,
                    resume_on_mouseout: false,
                    animation_speed: 700,
                    stack_on_small: false,
                    navigation_arrows: true,
                    slide_number: false,
                    bullets: true,
                    timer: false,
                    variable_height: false
                },
                dropdown: {},
                offcanvas: {
                    open_method: "move",
                    close_on_click: true
                }
            });
        }
    };
    CMS.UI = {
        init: function() {
            CMS.Forms.ajaxSubmittedForm("#contactform_form", "#contactFormBtn", "json", true, true);
            CMS.Forms.ajaxSubmittedForm("#commentform_form", "#submitCommentBtn", "json", true, true);
            CMS.Forms.ajaxSubmittedForm("#sonata_user_custom_user_registration_form", "#userRegisterFormBtn", "json", false, false);
            CMS.Forms.ajaxSubmittedForm("#sonata_user_generic_details_form", "#userGenericDetailsFormBtn", "json", true, false);
            CMS.Forms.ajaxSubmittedForm("#sonata_user_contact_details_form", "#userContactDetailsFormBtn", "json", true, false);
            CMS.Forms.ajaxSubmittedForm("#sonata_user_account_preferences_form", "#userAccountPreferencesFormBtn", "json", true, false);
            CMS.Forms.ajaxSubmittedForm("#sonata_user_change_password_form", "#userPasswordFormBtn", "json", true, true);
            CMS.Forms.ajaxSubmittedForm("#sonata_user_resetting_request", "#userResetPasswordFormBtn", "json", false, false);
            CMS.Forms.ajaxSubmittedForm("#sonata_user_resetting_form", "#userResetFormBtn", "json", false, false);
            CMS.Forms.ajaxSubmittedForm("#user_login_form", "#loginBtn", "json", false, false);
            CMS.Forms.datepicker();
            CMS.Forms.setupFilters();
        }
    };
    CMS.Forms = {
        $datepickerInputs: $(".datepickerField"),
        setupFilters: function() {
            $("#resetFilters").change(function() {
                var checkboxes = $(this).closest("form").find(":checkbox").not(this);
                checkboxes.removeAttr("checked");
            });
        },
        ajaxSubmittedForm: function(formId, formSubmitBtnId, dataType, overrideSuccess, resetForm) {
            var formElement = $(formId);
            var btnElement = $(formSubmitBtnId);
            if (formElement.length > 0) {
                btnElement.on("click", function(e) {
                    e.preventDefault();
                    btnElement.prop("disabled", true);
                    btnElement.addClass("sending");
                    var formData = formElement.serializeArray();
                    formData.push({
                        name: "isAjax",
                        value: "true"
                    });
                    var formAction = formElement.attr("action");
                    var $formAjaxRequest = $.post(formAction, formData, null, dataType);
                    $formAjaxRequest.always(function() {
                        btnElement.prop("disabled", false);
                        btnElement.removeClass("sending");
                    });
                    $formAjaxRequest.done(function(responseData) {
                        $(".formError").remove();
                        $(".formSuccess").remove();
                        $("label.error").removeClass("error");
                        $("input.error").removeClass("error");
                        $("select.error").removeClass("error");
                        $("textarea.error").removeClass("error");
                        if (responseData.hasErrors === false) {
                            if (responseData.newComment !== null && responseData.newComment.length > 0) {
                                console.log(responseData.newComment);
                                var commentHtml = '<div class="row comment odd">';
                                commentHtml += '<div id="comment-' + responseData.newComment[0].id + '" class="large-12 small-12 columns panel">';
                                commentHtml += "<h4>" + responseData.newComment[0].title + "</h4>";
                                commentHtml += "<p>" + responseData.newComment[0].comment + "</p></div></div>";
                                $(".previous-comments").prepend(commentHtml);
                            }
                            if (overrideSuccess) {
                                if (resetForm) {
                                    formElement.trigger("reset");
                                }
                                if (responseData.formMessage && responseData.formMessage !== "") {
                                    $('<small class="formSuccess alert-box success">' + responseData.formMessage + "</small>").hide().insertAfter(btnElement);
                                    $(".formSuccess").fadeIn(200);
                                }
                            } else if (responseData.redirectURL) {
                                window.location.href = responseData.redirectURL;
                            }
                        } else {
                            if (responseData.errors !== null) {
                                var errorArray = responseData.errors;
                                $.each(errorArray, function(key, val) {
                                    if (val.hasOwnProperty("first")) {
                                        $(formId + "_" + key + "_first").addClass("error");
                                        $(formId + "_" + key + "_first").after($('<small class="formError error">' + val.first[0] + "</small>").hide());
                                    } else if (val.hasOwnProperty("second")) {
                                        $(formId + "_" + key + "_second").addClass("error");
                                        $(formId + "_" + key + "_second").after($('<small class="formError error">' + val.second[0] + "</small>").hide());
                                    } else {
                                        $(formId + "_" + key).addClass("error");
                                        $(formId + "_" + key).after($('<small class="formError error">' + val[0] + "</small>").hide());
                                    }
                                });
                            }
                            if (responseData.formMessage && responseData.formMessage !== "") {
                                $('<small class="formError alert-box alert">' + responseData.formMessage + "</small>").hide().insertBefore(btnElement);
                            }
                            $(".formError").fadeIn(200);
                        }
                    });
                    $formAjaxRequest.fail(function(responseData, statusText, xhr) {
                        $(".formError").remove();
                        $(".formSuccess").remove();
                        $("label.error").removeClass("error");
                        $('<small class="formError alert-box alert">There was a ' + statusText + " error submitting the details. Please try again.</small>").hide().insertAfter(btnElement);
                        $(".formError").fadeIn(200);
                    });
                });
            }
        },
        datepicker: function() {
            CMS.Forms.$datepickerInputs.fdatepicker({
                autoShow: true,
                disableDblClickSelection: false,
                closeButton: true,
                pickTime: false,
                isInline: false
            });
        }
    };
    CMS.touch = {
        init: function() {}
    };
    CMS.mobileSpecific = {
        init: function() {}
    };
    CMS.windowResize = {
        init: function() {
            $(window).smartresize(function() {
                notifications.sendNotification(notifications.WINDOW_RESIZE);
            });
        }
    };
    CMS.sampleTest = {
        simpleTest: function(projectName) {
            this.projectName = projectName;
            return this.projectName + " is starting. Welcome!";
        }
    };
})(window.CMS = window.CMS || {}, jQuery, window, document);
//# sourceMappingURL=scripts.min.js.map