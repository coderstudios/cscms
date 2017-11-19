webpackJsonp([2],{

/***/ 10:
/***/ (function(module, exports) {

module.exports = function(module) {
	if(!module.webpackPolyfill) {
		module.deprecate = function() {};
		module.paths = [];
		// module.parent = undefined by default
		if(!module.children) module.children = [];
		Object.defineProperty(module, "loaded", {
			enumerable: true,
			get: function() {
				return module.l;
			}
		});
		Object.defineProperty(module, "id", {
			enumerable: true,
			get: function() {
				return module.i;
			}
		});
		module.webpackPolyfill = 1;
	}
	return module;
};


/***/ }),

/***/ 34:
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(module) {
/*
 *
 * More info at [www.dropzonejs.com](http://www.dropzonejs.com)
 *
 * Copyright (c) 2012, Matias Meno
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 */

(function() {
  var Dropzone, Emitter, camelize, contentLoaded, detectVerticalSquash, drawImageIOSFix, noop, without,
    __slice = [].slice,
    __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  noop = function() {};

  Emitter = (function() {
    function Emitter() {}

    Emitter.prototype.addEventListener = Emitter.prototype.on;

    Emitter.prototype.on = function(event, fn) {
      this._callbacks = this._callbacks || {};
      if (!this._callbacks[event]) {
        this._callbacks[event] = [];
      }
      this._callbacks[event].push(fn);
      return this;
    };

    Emitter.prototype.emit = function() {
      var args, callback, callbacks, event, _i, _len;
      event = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
      this._callbacks = this._callbacks || {};
      callbacks = this._callbacks[event];
      if (callbacks) {
        for (_i = 0, _len = callbacks.length; _i < _len; _i++) {
          callback = callbacks[_i];
          callback.apply(this, args);
        }
      }
      return this;
    };

    Emitter.prototype.removeListener = Emitter.prototype.off;

    Emitter.prototype.removeAllListeners = Emitter.prototype.off;

    Emitter.prototype.removeEventListener = Emitter.prototype.off;

    Emitter.prototype.off = function(event, fn) {
      var callback, callbacks, i, _i, _len;
      if (!this._callbacks || arguments.length === 0) {
        this._callbacks = {};
        return this;
      }
      callbacks = this._callbacks[event];
      if (!callbacks) {
        return this;
      }
      if (arguments.length === 1) {
        delete this._callbacks[event];
        return this;
      }
      for (i = _i = 0, _len = callbacks.length; _i < _len; i = ++_i) {
        callback = callbacks[i];
        if (callback === fn) {
          callbacks.splice(i, 1);
          break;
        }
      }
      return this;
    };

    return Emitter;

  })();

  Dropzone = (function(_super) {
    var extend, resolveOption;

    __extends(Dropzone, _super);

    Dropzone.prototype.Emitter = Emitter;


    /*
    This is a list of all available events you can register on a dropzone object.
    
    You can register an event handler like this:
    
        dropzone.on("dragEnter", function() { });
     */

    Dropzone.prototype.events = ["drop", "dragstart", "dragend", "dragenter", "dragover", "dragleave", "addedfile", "addedfiles", "removedfile", "thumbnail", "error", "errormultiple", "processing", "processingmultiple", "uploadprogress", "totaluploadprogress", "sending", "sendingmultiple", "success", "successmultiple", "canceled", "canceledmultiple", "complete", "completemultiple", "reset", "maxfilesexceeded", "maxfilesreached", "queuecomplete"];

    Dropzone.prototype.defaultOptions = {
      url: null,
      method: "post",
      withCredentials: false,
      parallelUploads: 2,
      uploadMultiple: false,
      maxFilesize: 256,
      paramName: "file",
      createImageThumbnails: true,
      maxThumbnailFilesize: 10,
      thumbnailWidth: 120,
      thumbnailHeight: 120,
      filesizeBase: 1000,
      maxFiles: null,
      params: {},
      clickable: true,
      ignoreHiddenFiles: true,
      acceptedFiles: null,
      acceptedMimeTypes: null,
      autoProcessQueue: true,
      autoQueue: true,
      addRemoveLinks: false,
      previewsContainer: null,
      hiddenInputContainer: "body",
      capture: null,
      renameFilename: null,
      dictDefaultMessage: "Drop files here to upload",
      dictFallbackMessage: "Your browser does not support drag'n'drop file uploads.",
      dictFallbackText: "Please use the fallback form below to upload your files like in the olden days.",
      dictFileTooBig: "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.",
      dictInvalidFileType: "You can't upload files of this type.",
      dictResponseError: "Server responded with {{statusCode}} code.",
      dictCancelUpload: "Cancel upload",
      dictCancelUploadConfirmation: "Are you sure you want to cancel this upload?",
      dictRemoveFile: "Remove file",
      dictRemoveFileConfirmation: null,
      dictMaxFilesExceeded: "You can not upload any more files.",
      accept: function(file, done) {
        return done();
      },
      init: function() {
        return noop;
      },
      forceFallback: false,
      fallback: function() {
        var child, messageElement, span, _i, _len, _ref;
        this.element.className = "" + this.element.className + " dz-browser-not-supported";
        _ref = this.element.getElementsByTagName("div");
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          child = _ref[_i];
          if (/(^| )dz-message($| )/.test(child.className)) {
            messageElement = child;
            child.className = "dz-message";
            continue;
          }
        }
        if (!messageElement) {
          messageElement = Dropzone.createElement("<div class=\"dz-message\"><span></span></div>");
          this.element.appendChild(messageElement);
        }
        span = messageElement.getElementsByTagName("span")[0];
        if (span) {
          if (span.textContent != null) {
            span.textContent = this.options.dictFallbackMessage;
          } else if (span.innerText != null) {
            span.innerText = this.options.dictFallbackMessage;
          }
        }
        return this.element.appendChild(this.getFallbackForm());
      },
      resize: function(file) {
        var info, srcRatio, trgRatio;
        info = {
          srcX: 0,
          srcY: 0,
          srcWidth: file.width,
          srcHeight: file.height
        };
        srcRatio = file.width / file.height;
        info.optWidth = this.options.thumbnailWidth;
        info.optHeight = this.options.thumbnailHeight;
        if ((info.optWidth == null) && (info.optHeight == null)) {
          info.optWidth = info.srcWidth;
          info.optHeight = info.srcHeight;
        } else if (info.optWidth == null) {
          info.optWidth = srcRatio * info.optHeight;
        } else if (info.optHeight == null) {
          info.optHeight = (1 / srcRatio) * info.optWidth;
        }
        trgRatio = info.optWidth / info.optHeight;
        if (file.height < info.optHeight || file.width < info.optWidth) {
          info.trgHeight = info.srcHeight;
          info.trgWidth = info.srcWidth;
        } else {
          if (srcRatio > trgRatio) {
            info.srcHeight = file.height;
            info.srcWidth = info.srcHeight * trgRatio;
          } else {
            info.srcWidth = file.width;
            info.srcHeight = info.srcWidth / trgRatio;
          }
        }
        info.srcX = (file.width - info.srcWidth) / 2;
        info.srcY = (file.height - info.srcHeight) / 2;
        return info;
      },

      /*
      Those functions register themselves to the events on init and handle all
      the user interface specific stuff. Overwriting them won't break the upload
      but can break the way it's displayed.
      You can overwrite them if you don't like the default behavior. If you just
      want to add an additional event handler, register it on the dropzone object
      and don't overwrite those options.
       */
      drop: function(e) {
        return this.element.classList.remove("dz-drag-hover");
      },
      dragstart: noop,
      dragend: function(e) {
        return this.element.classList.remove("dz-drag-hover");
      },
      dragenter: function(e) {
        return this.element.classList.add("dz-drag-hover");
      },
      dragover: function(e) {
        return this.element.classList.add("dz-drag-hover");
      },
      dragleave: function(e) {
        return this.element.classList.remove("dz-drag-hover");
      },
      paste: noop,
      reset: function() {
        return this.element.classList.remove("dz-started");
      },
      addedfile: function(file) {
        var node, removeFileEvent, removeLink, _i, _j, _k, _len, _len1, _len2, _ref, _ref1, _ref2, _results;
        if (this.element === this.previewsContainer) {
          this.element.classList.add("dz-started");
        }
        if (this.previewsContainer) {
          file.previewElement = Dropzone.createElement(this.options.previewTemplate.trim());
          file.previewTemplate = file.previewElement;
          this.previewsContainer.appendChild(file.previewElement);
          _ref = file.previewElement.querySelectorAll("[data-dz-name]");
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i];
            node.textContent = this._renameFilename(file.name);
          }
          _ref1 = file.previewElement.querySelectorAll("[data-dz-size]");
          for (_j = 0, _len1 = _ref1.length; _j < _len1; _j++) {
            node = _ref1[_j];
            node.innerHTML = this.filesize(file.size);
          }
          if (this.options.addRemoveLinks) {
            file._removeLink = Dropzone.createElement("<a class=\"dz-remove\" href=\"javascript:undefined;\" data-dz-remove>" + this.options.dictRemoveFile + "</a>");
            file.previewElement.appendChild(file._removeLink);
          }
          removeFileEvent = (function(_this) {
            return function(e) {
              e.preventDefault();
              e.stopPropagation();
              if (file.status === Dropzone.UPLOADING) {
                return Dropzone.confirm(_this.options.dictCancelUploadConfirmation, function() {
                  return _this.removeFile(file);
                });
              } else {
                if (_this.options.dictRemoveFileConfirmation) {
                  return Dropzone.confirm(_this.options.dictRemoveFileConfirmation, function() {
                    return _this.removeFile(file);
                  });
                } else {
                  return _this.removeFile(file);
                }
              }
            };
          })(this);
          _ref2 = file.previewElement.querySelectorAll("[data-dz-remove]");
          _results = [];
          for (_k = 0, _len2 = _ref2.length; _k < _len2; _k++) {
            removeLink = _ref2[_k];
            _results.push(removeLink.addEventListener("click", removeFileEvent));
          }
          return _results;
        }
      },
      removedfile: function(file) {
        var _ref;
        if (file.previewElement) {
          if ((_ref = file.previewElement) != null) {
            _ref.parentNode.removeChild(file.previewElement);
          }
        }
        return this._updateMaxFilesReachedClass();
      },
      thumbnail: function(file, dataUrl) {
        var thumbnailElement, _i, _len, _ref;
        if (file.previewElement) {
          file.previewElement.classList.remove("dz-file-preview");
          _ref = file.previewElement.querySelectorAll("[data-dz-thumbnail]");
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            thumbnailElement = _ref[_i];
            thumbnailElement.alt = file.name;
            thumbnailElement.src = dataUrl;
          }
          return setTimeout(((function(_this) {
            return function() {
              return file.previewElement.classList.add("dz-image-preview");
            };
          })(this)), 1);
        }
      },
      error: function(file, message) {
        var node, _i, _len, _ref, _results;
        if (file.previewElement) {
          file.previewElement.classList.add("dz-error");
          if (typeof message !== "String" && message.error) {
            message = message.error;
          }
          _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
          _results = [];
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i];
            _results.push(node.textContent = message);
          }
          return _results;
        }
      },
      errormultiple: noop,
      processing: function(file) {
        if (file.previewElement) {
          file.previewElement.classList.add("dz-processing");
          if (file._removeLink) {
            return file._removeLink.textContent = this.options.dictCancelUpload;
          }
        }
      },
      processingmultiple: noop,
      uploadprogress: function(file, progress, bytesSent) {
        var node, _i, _len, _ref, _results;
        if (file.previewElement) {
          _ref = file.previewElement.querySelectorAll("[data-dz-uploadprogress]");
          _results = [];
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i];
            if (node.nodeName === 'PROGRESS') {
              _results.push(node.value = progress);
            } else {
              _results.push(node.style.width = "" + progress + "%");
            }
          }
          return _results;
        }
      },
      totaluploadprogress: noop,
      sending: noop,
      sendingmultiple: noop,
      success: function(file) {
        if (file.previewElement) {
          return file.previewElement.classList.add("dz-success");
        }
      },
      successmultiple: noop,
      canceled: function(file) {
        return this.emit("error", file, "Upload canceled.");
      },
      canceledmultiple: noop,
      complete: function(file) {
        if (file._removeLink) {
          file._removeLink.textContent = this.options.dictRemoveFile;
        }
        if (file.previewElement) {
          return file.previewElement.classList.add("dz-complete");
        }
      },
      completemultiple: noop,
      maxfilesexceeded: noop,
      maxfilesreached: noop,
      queuecomplete: noop,
      addedfiles: noop,
      previewTemplate: "<div class=\"dz-preview dz-file-preview\">\n  <div class=\"dz-image\"><img data-dz-thumbnail /></div>\n  <div class=\"dz-details\">\n    <div class=\"dz-size\"><span data-dz-size></span></div>\n    <div class=\"dz-filename\"><span data-dz-name></span></div>\n  </div>\n  <div class=\"dz-progress\"><span class=\"dz-upload\" data-dz-uploadprogress></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n  <div class=\"dz-success-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:sketch=\"http://www.bohemiancoding.com/sketch/ns\">\n      <title>Check</title>\n      <defs></defs>\n      <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" sketch:type=\"MSPage\">\n        <path d=\"M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" id=\"Oval-2\" stroke-opacity=\"0.198794158\" stroke=\"#747474\" fill-opacity=\"0.816519475\" fill=\"#FFFFFF\" sketch:type=\"MSShapeGroup\"></path>\n      </g>\n    </svg>\n  </div>\n  <div class=\"dz-error-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:sketch=\"http://www.bohemiancoding.com/sketch/ns\">\n      <title>Error</title>\n      <defs></defs>\n      <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" sketch:type=\"MSPage\">\n        <g id=\"Check-+-Oval-2\" sketch:type=\"MSLayerGroup\" stroke=\"#747474\" stroke-opacity=\"0.198794158\" fill=\"#FFFFFF\" fill-opacity=\"0.816519475\">\n          <path d=\"M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" id=\"Oval-2\" sketch:type=\"MSShapeGroup\"></path>\n        </g>\n      </g>\n    </svg>\n  </div>\n</div>"
    };

    extend = function() {
      var key, object, objects, target, val, _i, _len;
      target = arguments[0], objects = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
      for (_i = 0, _len = objects.length; _i < _len; _i++) {
        object = objects[_i];
        for (key in object) {
          val = object[key];
          target[key] = val;
        }
      }
      return target;
    };

    function Dropzone(element, options) {
      var elementOptions, fallback, _ref;
      this.element = element;
      this.version = Dropzone.version;
      this.defaultOptions.previewTemplate = this.defaultOptions.previewTemplate.replace(/\n*/g, "");
      this.clickableElements = [];
      this.listeners = [];
      this.files = [];
      if (typeof this.element === "string") {
        this.element = document.querySelector(this.element);
      }
      if (!(this.element && (this.element.nodeType != null))) {
        throw new Error("Invalid dropzone element.");
      }
      if (this.element.dropzone) {
        throw new Error("Dropzone already attached.");
      }
      Dropzone.instances.push(this);
      this.element.dropzone = this;
      elementOptions = (_ref = Dropzone.optionsForElement(this.element)) != null ? _ref : {};
      this.options = extend({}, this.defaultOptions, elementOptions, options != null ? options : {});
      if (this.options.forceFallback || !Dropzone.isBrowserSupported()) {
        return this.options.fallback.call(this);
      }
      if (this.options.url == null) {
        this.options.url = this.element.getAttribute("action");
      }
      if (!this.options.url) {
        throw new Error("No URL provided.");
      }
      if (this.options.acceptedFiles && this.options.acceptedMimeTypes) {
        throw new Error("You can't provide both 'acceptedFiles' and 'acceptedMimeTypes'. 'acceptedMimeTypes' is deprecated.");
      }
      if (this.options.acceptedMimeTypes) {
        this.options.acceptedFiles = this.options.acceptedMimeTypes;
        delete this.options.acceptedMimeTypes;
      }
      this.options.method = this.options.method.toUpperCase();
      if ((fallback = this.getExistingFallback()) && fallback.parentNode) {
        fallback.parentNode.removeChild(fallback);
      }
      if (this.options.previewsContainer !== false) {
        if (this.options.previewsContainer) {
          this.previewsContainer = Dropzone.getElement(this.options.previewsContainer, "previewsContainer");
        } else {
          this.previewsContainer = this.element;
        }
      }
      if (this.options.clickable) {
        if (this.options.clickable === true) {
          this.clickableElements = [this.element];
        } else {
          this.clickableElements = Dropzone.getElements(this.options.clickable, "clickable");
        }
      }
      this.init();
    }

    Dropzone.prototype.getAcceptedFiles = function() {
      var file, _i, _len, _ref, _results;
      _ref = this.files;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        file = _ref[_i];
        if (file.accepted) {
          _results.push(file);
        }
      }
      return _results;
    };

    Dropzone.prototype.getRejectedFiles = function() {
      var file, _i, _len, _ref, _results;
      _ref = this.files;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        file = _ref[_i];
        if (!file.accepted) {
          _results.push(file);
        }
      }
      return _results;
    };

    Dropzone.prototype.getFilesWithStatus = function(status) {
      var file, _i, _len, _ref, _results;
      _ref = this.files;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        file = _ref[_i];
        if (file.status === status) {
          _results.push(file);
        }
      }
      return _results;
    };

    Dropzone.prototype.getQueuedFiles = function() {
      return this.getFilesWithStatus(Dropzone.QUEUED);
    };

    Dropzone.prototype.getUploadingFiles = function() {
      return this.getFilesWithStatus(Dropzone.UPLOADING);
    };

    Dropzone.prototype.getAddedFiles = function() {
      return this.getFilesWithStatus(Dropzone.ADDED);
    };

    Dropzone.prototype.getActiveFiles = function() {
      var file, _i, _len, _ref, _results;
      _ref = this.files;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        file = _ref[_i];
        if (file.status === Dropzone.UPLOADING || file.status === Dropzone.QUEUED) {
          _results.push(file);
        }
      }
      return _results;
    };

    Dropzone.prototype.init = function() {
      var eventName, noPropagation, setupHiddenFileInput, _i, _len, _ref, _ref1;
      if (this.element.tagName === "form") {
        this.element.setAttribute("enctype", "multipart/form-data");
      }
      if (this.element.classList.contains("dropzone") && !this.element.querySelector(".dz-message")) {
        this.element.appendChild(Dropzone.createElement("<div class=\"dz-default dz-message\"><span>" + this.options.dictDefaultMessage + "</span></div>"));
      }
      if (this.clickableElements.length) {
        setupHiddenFileInput = (function(_this) {
          return function() {
            if (_this.hiddenFileInput) {
              _this.hiddenFileInput.parentNode.removeChild(_this.hiddenFileInput);
            }
            _this.hiddenFileInput = document.createElement("input");
            _this.hiddenFileInput.setAttribute("type", "file");
            if ((_this.options.maxFiles == null) || _this.options.maxFiles > 1) {
              _this.hiddenFileInput.setAttribute("multiple", "multiple");
            }
            _this.hiddenFileInput.className = "dz-hidden-input";
            if (_this.options.acceptedFiles != null) {
              _this.hiddenFileInput.setAttribute("accept", _this.options.acceptedFiles);
            }
            if (_this.options.capture != null) {
              _this.hiddenFileInput.setAttribute("capture", _this.options.capture);
            }
            _this.hiddenFileInput.style.visibility = "hidden";
            _this.hiddenFileInput.style.position = "absolute";
            _this.hiddenFileInput.style.top = "0";
            _this.hiddenFileInput.style.left = "0";
            _this.hiddenFileInput.style.height = "0";
            _this.hiddenFileInput.style.width = "0";
            document.querySelector(_this.options.hiddenInputContainer).appendChild(_this.hiddenFileInput);
            return _this.hiddenFileInput.addEventListener("change", function() {
              var file, files, _i, _len;
              files = _this.hiddenFileInput.files;
              if (files.length) {
                for (_i = 0, _len = files.length; _i < _len; _i++) {
                  file = files[_i];
                  _this.addFile(file);
                }
              }
              _this.emit("addedfiles", files);
              return setupHiddenFileInput();
            });
          };
        })(this);
        setupHiddenFileInput();
      }
      this.URL = (_ref = window.URL) != null ? _ref : window.webkitURL;
      _ref1 = this.events;
      for (_i = 0, _len = _ref1.length; _i < _len; _i++) {
        eventName = _ref1[_i];
        this.on(eventName, this.options[eventName]);
      }
      this.on("uploadprogress", (function(_this) {
        return function() {
          return _this.updateTotalUploadProgress();
        };
      })(this));
      this.on("removedfile", (function(_this) {
        return function() {
          return _this.updateTotalUploadProgress();
        };
      })(this));
      this.on("canceled", (function(_this) {
        return function(file) {
          return _this.emit("complete", file);
        };
      })(this));
      this.on("complete", (function(_this) {
        return function(file) {
          if (_this.getAddedFiles().length === 0 && _this.getUploadingFiles().length === 0 && _this.getQueuedFiles().length === 0) {
            return setTimeout((function() {
              return _this.emit("queuecomplete");
            }), 0);
          }
        };
      })(this));
      noPropagation = function(e) {
        e.stopPropagation();
        if (e.preventDefault) {
          return e.preventDefault();
        } else {
          return e.returnValue = false;
        }
      };
      this.listeners = [
        {
          element: this.element,
          events: {
            "dragstart": (function(_this) {
              return function(e) {
                return _this.emit("dragstart", e);
              };
            })(this),
            "dragenter": (function(_this) {
              return function(e) {
                noPropagation(e);
                return _this.emit("dragenter", e);
              };
            })(this),
            "dragover": (function(_this) {
              return function(e) {
                var efct;
                try {
                  efct = e.dataTransfer.effectAllowed;
                } catch (_error) {}
                e.dataTransfer.dropEffect = 'move' === efct || 'linkMove' === efct ? 'move' : 'copy';
                noPropagation(e);
                return _this.emit("dragover", e);
              };
            })(this),
            "dragleave": (function(_this) {
              return function(e) {
                return _this.emit("dragleave", e);
              };
            })(this),
            "drop": (function(_this) {
              return function(e) {
                noPropagation(e);
                return _this.drop(e);
              };
            })(this),
            "dragend": (function(_this) {
              return function(e) {
                return _this.emit("dragend", e);
              };
            })(this)
          }
        }
      ];
      this.clickableElements.forEach((function(_this) {
        return function(clickableElement) {
          return _this.listeners.push({
            element: clickableElement,
            events: {
              "click": function(evt) {
                if ((clickableElement !== _this.element) || (evt.target === _this.element || Dropzone.elementInside(evt.target, _this.element.querySelector(".dz-message")))) {
                  _this.hiddenFileInput.click();
                }
                return true;
              }
            }
          });
        };
      })(this));
      this.enable();
      return this.options.init.call(this);
    };

    Dropzone.prototype.destroy = function() {
      var _ref;
      this.disable();
      this.removeAllFiles(true);
      if ((_ref = this.hiddenFileInput) != null ? _ref.parentNode : void 0) {
        this.hiddenFileInput.parentNode.removeChild(this.hiddenFileInput);
        this.hiddenFileInput = null;
      }
      delete this.element.dropzone;
      return Dropzone.instances.splice(Dropzone.instances.indexOf(this), 1);
    };

    Dropzone.prototype.updateTotalUploadProgress = function() {
      var activeFiles, file, totalBytes, totalBytesSent, totalUploadProgress, _i, _len, _ref;
      totalBytesSent = 0;
      totalBytes = 0;
      activeFiles = this.getActiveFiles();
      if (activeFiles.length) {
        _ref = this.getActiveFiles();
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          file = _ref[_i];
          totalBytesSent += file.upload.bytesSent;
          totalBytes += file.upload.total;
        }
        totalUploadProgress = 100 * totalBytesSent / totalBytes;
      } else {
        totalUploadProgress = 100;
      }
      return this.emit("totaluploadprogress", totalUploadProgress, totalBytes, totalBytesSent);
    };

    Dropzone.prototype._getParamName = function(n) {
      if (typeof this.options.paramName === "function") {
        return this.options.paramName(n);
      } else {
        return "" + this.options.paramName + (this.options.uploadMultiple ? "[" + n + "]" : "");
      }
    };

    Dropzone.prototype._renameFilename = function(name) {
      if (typeof this.options.renameFilename !== "function") {
        return name;
      }
      return this.options.renameFilename(name);
    };

    Dropzone.prototype.getFallbackForm = function() {
      var existingFallback, fields, fieldsString, form;
      if (existingFallback = this.getExistingFallback()) {
        return existingFallback;
      }
      fieldsString = "<div class=\"dz-fallback\">";
      if (this.options.dictFallbackText) {
        fieldsString += "<p>" + this.options.dictFallbackText + "</p>";
      }
      fieldsString += "<input type=\"file\" name=\"" + (this._getParamName(0)) + "\" " + (this.options.uploadMultiple ? 'multiple="multiple"' : void 0) + " /><input type=\"submit\" value=\"Upload!\"></div>";
      fields = Dropzone.createElement(fieldsString);
      if (this.element.tagName !== "FORM") {
        form = Dropzone.createElement("<form action=\"" + this.options.url + "\" enctype=\"multipart/form-data\" method=\"" + this.options.method + "\"></form>");
        form.appendChild(fields);
      } else {
        this.element.setAttribute("enctype", "multipart/form-data");
        this.element.setAttribute("method", this.options.method);
      }
      return form != null ? form : fields;
    };

    Dropzone.prototype.getExistingFallback = function() {
      var fallback, getFallback, tagName, _i, _len, _ref;
      getFallback = function(elements) {
        var el, _i, _len;
        for (_i = 0, _len = elements.length; _i < _len; _i++) {
          el = elements[_i];
          if (/(^| )fallback($| )/.test(el.className)) {
            return el;
          }
        }
      };
      _ref = ["div", "form"];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        tagName = _ref[_i];
        if (fallback = getFallback(this.element.getElementsByTagName(tagName))) {
          return fallback;
        }
      }
    };

    Dropzone.prototype.setupEventListeners = function() {
      var elementListeners, event, listener, _i, _len, _ref, _results;
      _ref = this.listeners;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        elementListeners = _ref[_i];
        _results.push((function() {
          var _ref1, _results1;
          _ref1 = elementListeners.events;
          _results1 = [];
          for (event in _ref1) {
            listener = _ref1[event];
            _results1.push(elementListeners.element.addEventListener(event, listener, false));
          }
          return _results1;
        })());
      }
      return _results;
    };

    Dropzone.prototype.removeEventListeners = function() {
      var elementListeners, event, listener, _i, _len, _ref, _results;
      _ref = this.listeners;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        elementListeners = _ref[_i];
        _results.push((function() {
          var _ref1, _results1;
          _ref1 = elementListeners.events;
          _results1 = [];
          for (event in _ref1) {
            listener = _ref1[event];
            _results1.push(elementListeners.element.removeEventListener(event, listener, false));
          }
          return _results1;
        })());
      }
      return _results;
    };

    Dropzone.prototype.disable = function() {
      var file, _i, _len, _ref, _results;
      this.clickableElements.forEach(function(element) {
        return element.classList.remove("dz-clickable");
      });
      this.removeEventListeners();
      _ref = this.files;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        file = _ref[_i];
        _results.push(this.cancelUpload(file));
      }
      return _results;
    };

    Dropzone.prototype.enable = function() {
      this.clickableElements.forEach(function(element) {
        return element.classList.add("dz-clickable");
      });
      return this.setupEventListeners();
    };

    Dropzone.prototype.filesize = function(size) {
      var cutoff, i, selectedSize, selectedUnit, unit, units, _i, _len;
      selectedSize = 0;
      selectedUnit = "b";
      if (size > 0) {
        units = ['TB', 'GB', 'MB', 'KB', 'b'];
        for (i = _i = 0, _len = units.length; _i < _len; i = ++_i) {
          unit = units[i];
          cutoff = Math.pow(this.options.filesizeBase, 4 - i) / 10;
          if (size >= cutoff) {
            selectedSize = size / Math.pow(this.options.filesizeBase, 4 - i);
            selectedUnit = unit;
            break;
          }
        }
        selectedSize = Math.round(10 * selectedSize) / 10;
      }
      return "<strong>" + selectedSize + "</strong> " + selectedUnit;
    };

    Dropzone.prototype._updateMaxFilesReachedClass = function() {
      if ((this.options.maxFiles != null) && this.getAcceptedFiles().length >= this.options.maxFiles) {
        if (this.getAcceptedFiles().length === this.options.maxFiles) {
          this.emit('maxfilesreached', this.files);
        }
        return this.element.classList.add("dz-max-files-reached");
      } else {
        return this.element.classList.remove("dz-max-files-reached");
      }
    };

    Dropzone.prototype.drop = function(e) {
      var files, items;
      if (!e.dataTransfer) {
        return;
      }
      this.emit("drop", e);
      files = e.dataTransfer.files;
      this.emit("addedfiles", files);
      if (files.length) {
        items = e.dataTransfer.items;
        if (items && items.length && (items[0].webkitGetAsEntry != null)) {
          this._addFilesFromItems(items);
        } else {
          this.handleFiles(files);
        }
      }
    };

    Dropzone.prototype.paste = function(e) {
      var items, _ref;
      if ((e != null ? (_ref = e.clipboardData) != null ? _ref.items : void 0 : void 0) == null) {
        return;
      }
      this.emit("paste", e);
      items = e.clipboardData.items;
      if (items.length) {
        return this._addFilesFromItems(items);
      }
    };

    Dropzone.prototype.handleFiles = function(files) {
      var file, _i, _len, _results;
      _results = [];
      for (_i = 0, _len = files.length; _i < _len; _i++) {
        file = files[_i];
        _results.push(this.addFile(file));
      }
      return _results;
    };

    Dropzone.prototype._addFilesFromItems = function(items) {
      var entry, item, _i, _len, _results;
      _results = [];
      for (_i = 0, _len = items.length; _i < _len; _i++) {
        item = items[_i];
        if ((item.webkitGetAsEntry != null) && (entry = item.webkitGetAsEntry())) {
          if (entry.isFile) {
            _results.push(this.addFile(item.getAsFile()));
          } else if (entry.isDirectory) {
            _results.push(this._addFilesFromDirectory(entry, entry.name));
          } else {
            _results.push(void 0);
          }
        } else if (item.getAsFile != null) {
          if ((item.kind == null) || item.kind === "file") {
            _results.push(this.addFile(item.getAsFile()));
          } else {
            _results.push(void 0);
          }
        } else {
          _results.push(void 0);
        }
      }
      return _results;
    };

    Dropzone.prototype._addFilesFromDirectory = function(directory, path) {
      var dirReader, errorHandler, readEntries;
      dirReader = directory.createReader();
      errorHandler = function(error) {
        return typeof console !== "undefined" && console !== null ? typeof console.log === "function" ? console.log(error) : void 0 : void 0;
      };
      readEntries = (function(_this) {
        return function() {
          return dirReader.readEntries(function(entries) {
            var entry, _i, _len;
            if (entries.length > 0) {
              for (_i = 0, _len = entries.length; _i < _len; _i++) {
                entry = entries[_i];
                if (entry.isFile) {
                  entry.file(function(file) {
                    if (_this.options.ignoreHiddenFiles && file.name.substring(0, 1) === '.') {
                      return;
                    }
                    file.fullPath = "" + path + "/" + file.name;
                    return _this.addFile(file);
                  });
                } else if (entry.isDirectory) {
                  _this._addFilesFromDirectory(entry, "" + path + "/" + entry.name);
                }
              }
              readEntries();
            }
            return null;
          }, errorHandler);
        };
      })(this);
      return readEntries();
    };

    Dropzone.prototype.accept = function(file, done) {
      if (file.size > this.options.maxFilesize * 1024 * 1024) {
        return done(this.options.dictFileTooBig.replace("{{filesize}}", Math.round(file.size / 1024 / 10.24) / 100).replace("{{maxFilesize}}", this.options.maxFilesize));
      } else if (!Dropzone.isValidFile(file, this.options.acceptedFiles)) {
        return done(this.options.dictInvalidFileType);
      } else if ((this.options.maxFiles != null) && this.getAcceptedFiles().length >= this.options.maxFiles) {
        done(this.options.dictMaxFilesExceeded.replace("{{maxFiles}}", this.options.maxFiles));
        return this.emit("maxfilesexceeded", file);
      } else {
        return this.options.accept.call(this, file, done);
      }
    };

    Dropzone.prototype.addFile = function(file) {
      file.upload = {
        progress: 0,
        total: file.size,
        bytesSent: 0
      };
      this.files.push(file);
      file.status = Dropzone.ADDED;
      this.emit("addedfile", file);
      this._enqueueThumbnail(file);
      return this.accept(file, (function(_this) {
        return function(error) {
          if (error) {
            file.accepted = false;
            _this._errorProcessing([file], error);
          } else {
            file.accepted = true;
            if (_this.options.autoQueue) {
              _this.enqueueFile(file);
            }
          }
          return _this._updateMaxFilesReachedClass();
        };
      })(this));
    };

    Dropzone.prototype.enqueueFiles = function(files) {
      var file, _i, _len;
      for (_i = 0, _len = files.length; _i < _len; _i++) {
        file = files[_i];
        this.enqueueFile(file);
      }
      return null;
    };

    Dropzone.prototype.enqueueFile = function(file) {
      if (file.status === Dropzone.ADDED && file.accepted === true) {
        file.status = Dropzone.QUEUED;
        if (this.options.autoProcessQueue) {
          return setTimeout(((function(_this) {
            return function() {
              return _this.processQueue();
            };
          })(this)), 0);
        }
      } else {
        throw new Error("This file can't be queued because it has already been processed or was rejected.");
      }
    };

    Dropzone.prototype._thumbnailQueue = [];

    Dropzone.prototype._processingThumbnail = false;

    Dropzone.prototype._enqueueThumbnail = function(file) {
      if (this.options.createImageThumbnails && file.type.match(/image.*/) && file.size <= this.options.maxThumbnailFilesize * 1024 * 1024) {
        this._thumbnailQueue.push(file);
        return setTimeout(((function(_this) {
          return function() {
            return _this._processThumbnailQueue();
          };
        })(this)), 0);
      }
    };

    Dropzone.prototype._processThumbnailQueue = function() {
      if (this._processingThumbnail || this._thumbnailQueue.length === 0) {
        return;
      }
      this._processingThumbnail = true;
      return this.createThumbnail(this._thumbnailQueue.shift(), (function(_this) {
        return function() {
          _this._processingThumbnail = false;
          return _this._processThumbnailQueue();
        };
      })(this));
    };

    Dropzone.prototype.removeFile = function(file) {
      if (file.status === Dropzone.UPLOADING) {
        this.cancelUpload(file);
      }
      this.files = without(this.files, file);
      this.emit("removedfile", file);
      if (this.files.length === 0) {
        return this.emit("reset");
      }
    };

    Dropzone.prototype.removeAllFiles = function(cancelIfNecessary) {
      var file, _i, _len, _ref;
      if (cancelIfNecessary == null) {
        cancelIfNecessary = false;
      }
      _ref = this.files.slice();
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        file = _ref[_i];
        if (file.status !== Dropzone.UPLOADING || cancelIfNecessary) {
          this.removeFile(file);
        }
      }
      return null;
    };

    Dropzone.prototype.createThumbnail = function(file, callback) {
      var fileReader;
      fileReader = new FileReader;
      fileReader.onload = (function(_this) {
        return function() {
          if (file.type === "image/svg+xml") {
            _this.emit("thumbnail", file, fileReader.result);
            if (callback != null) {
              callback();
            }
            return;
          }
          return _this.createThumbnailFromUrl(file, fileReader.result, callback);
        };
      })(this);
      return fileReader.readAsDataURL(file);
    };

    Dropzone.prototype.createThumbnailFromUrl = function(file, imageUrl, callback, crossOrigin) {
      var img;
      img = document.createElement("img");
      if (crossOrigin) {
        img.crossOrigin = crossOrigin;
      }
      img.onload = (function(_this) {
        return function() {
          var canvas, ctx, resizeInfo, thumbnail, _ref, _ref1, _ref2, _ref3;
          file.width = img.width;
          file.height = img.height;
          resizeInfo = _this.options.resize.call(_this, file);
          if (resizeInfo.trgWidth == null) {
            resizeInfo.trgWidth = resizeInfo.optWidth;
          }
          if (resizeInfo.trgHeight == null) {
            resizeInfo.trgHeight = resizeInfo.optHeight;
          }
          canvas = document.createElement("canvas");
          ctx = canvas.getContext("2d");
          canvas.width = resizeInfo.trgWidth;
          canvas.height = resizeInfo.trgHeight;
          drawImageIOSFix(ctx, img, (_ref = resizeInfo.srcX) != null ? _ref : 0, (_ref1 = resizeInfo.srcY) != null ? _ref1 : 0, resizeInfo.srcWidth, resizeInfo.srcHeight, (_ref2 = resizeInfo.trgX) != null ? _ref2 : 0, (_ref3 = resizeInfo.trgY) != null ? _ref3 : 0, resizeInfo.trgWidth, resizeInfo.trgHeight);
          thumbnail = canvas.toDataURL("image/png");
          _this.emit("thumbnail", file, thumbnail);
          if (callback != null) {
            return callback();
          }
        };
      })(this);
      if (callback != null) {
        img.onerror = callback;
      }
      return img.src = imageUrl;
    };

    Dropzone.prototype.processQueue = function() {
      var i, parallelUploads, processingLength, queuedFiles;
      parallelUploads = this.options.parallelUploads;
      processingLength = this.getUploadingFiles().length;
      i = processingLength;
      if (processingLength >= parallelUploads) {
        return;
      }
      queuedFiles = this.getQueuedFiles();
      if (!(queuedFiles.length > 0)) {
        return;
      }
      if (this.options.uploadMultiple) {
        return this.processFiles(queuedFiles.slice(0, parallelUploads - processingLength));
      } else {
        while (i < parallelUploads) {
          if (!queuedFiles.length) {
            return;
          }
          this.processFile(queuedFiles.shift());
          i++;
        }
      }
    };

    Dropzone.prototype.processFile = function(file) {
      return this.processFiles([file]);
    };

    Dropzone.prototype.processFiles = function(files) {
      var file, _i, _len;
      for (_i = 0, _len = files.length; _i < _len; _i++) {
        file = files[_i];
        file.processing = true;
        file.status = Dropzone.UPLOADING;
        this.emit("processing", file);
      }
      if (this.options.uploadMultiple) {
        this.emit("processingmultiple", files);
      }
      return this.uploadFiles(files);
    };

    Dropzone.prototype._getFilesWithXhr = function(xhr) {
      var file, files;
      return files = (function() {
        var _i, _len, _ref, _results;
        _ref = this.files;
        _results = [];
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          file = _ref[_i];
          if (file.xhr === xhr) {
            _results.push(file);
          }
        }
        return _results;
      }).call(this);
    };

    Dropzone.prototype.cancelUpload = function(file) {
      var groupedFile, groupedFiles, _i, _j, _len, _len1, _ref;
      if (file.status === Dropzone.UPLOADING) {
        groupedFiles = this._getFilesWithXhr(file.xhr);
        for (_i = 0, _len = groupedFiles.length; _i < _len; _i++) {
          groupedFile = groupedFiles[_i];
          groupedFile.status = Dropzone.CANCELED;
        }
        file.xhr.abort();
        for (_j = 0, _len1 = groupedFiles.length; _j < _len1; _j++) {
          groupedFile = groupedFiles[_j];
          this.emit("canceled", groupedFile);
        }
        if (this.options.uploadMultiple) {
          this.emit("canceledmultiple", groupedFiles);
        }
      } else if ((_ref = file.status) === Dropzone.ADDED || _ref === Dropzone.QUEUED) {
        file.status = Dropzone.CANCELED;
        this.emit("canceled", file);
        if (this.options.uploadMultiple) {
          this.emit("canceledmultiple", [file]);
        }
      }
      if (this.options.autoProcessQueue) {
        return this.processQueue();
      }
    };

    resolveOption = function() {
      var args, option;
      option = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
      if (typeof option === 'function') {
        return option.apply(this, args);
      }
      return option;
    };

    Dropzone.prototype.uploadFile = function(file) {
      return this.uploadFiles([file]);
    };

    Dropzone.prototype.uploadFiles = function(files) {
      var file, formData, handleError, headerName, headerValue, headers, i, input, inputName, inputType, key, method, option, progressObj, response, updateProgress, url, value, xhr, _i, _j, _k, _l, _len, _len1, _len2, _len3, _m, _ref, _ref1, _ref2, _ref3, _ref4, _ref5;
      xhr = new XMLHttpRequest();
      for (_i = 0, _len = files.length; _i < _len; _i++) {
        file = files[_i];
        file.xhr = xhr;
      }
      method = resolveOption(this.options.method, files);
      url = resolveOption(this.options.url, files);
      xhr.open(method, url, true);
      xhr.withCredentials = !!this.options.withCredentials;
      response = null;
      handleError = (function(_this) {
        return function() {
          var _j, _len1, _results;
          _results = [];
          for (_j = 0, _len1 = files.length; _j < _len1; _j++) {
            file = files[_j];
            _results.push(_this._errorProcessing(files, response || _this.options.dictResponseError.replace("{{statusCode}}", xhr.status), xhr));
          }
          return _results;
        };
      })(this);
      updateProgress = (function(_this) {
        return function(e) {
          var allFilesFinished, progress, _j, _k, _l, _len1, _len2, _len3, _results;
          if (e != null) {
            progress = 100 * e.loaded / e.total;
            for (_j = 0, _len1 = files.length; _j < _len1; _j++) {
              file = files[_j];
              file.upload = {
                progress: progress,
                total: e.total,
                bytesSent: e.loaded
              };
            }
          } else {
            allFilesFinished = true;
            progress = 100;
            for (_k = 0, _len2 = files.length; _k < _len2; _k++) {
              file = files[_k];
              if (!(file.upload.progress === 100 && file.upload.bytesSent === file.upload.total)) {
                allFilesFinished = false;
              }
              file.upload.progress = progress;
              file.upload.bytesSent = file.upload.total;
            }
            if (allFilesFinished) {
              return;
            }
          }
          _results = [];
          for (_l = 0, _len3 = files.length; _l < _len3; _l++) {
            file = files[_l];
            _results.push(_this.emit("uploadprogress", file, progress, file.upload.bytesSent));
          }
          return _results;
        };
      })(this);
      xhr.onload = (function(_this) {
        return function(e) {
          var _ref;
          if (files[0].status === Dropzone.CANCELED) {
            return;
          }
          if (xhr.readyState !== 4) {
            return;
          }
          response = xhr.responseText;
          if (xhr.getResponseHeader("content-type") && ~xhr.getResponseHeader("content-type").indexOf("application/json")) {
            try {
              response = JSON.parse(response);
            } catch (_error) {
              e = _error;
              response = "Invalid JSON response from server.";
            }
          }
          updateProgress();
          if (!((200 <= (_ref = xhr.status) && _ref < 300))) {
            return handleError();
          } else {
            return _this._finished(files, response, e);
          }
        };
      })(this);
      xhr.onerror = (function(_this) {
        return function() {
          if (files[0].status === Dropzone.CANCELED) {
            return;
          }
          return handleError();
        };
      })(this);
      progressObj = (_ref = xhr.upload) != null ? _ref : xhr;
      progressObj.onprogress = updateProgress;
      headers = {
        "Accept": "application/json",
        "Cache-Control": "no-cache",
        "X-Requested-With": "XMLHttpRequest"
      };
      if (this.options.headers) {
        extend(headers, this.options.headers);
      }
      for (headerName in headers) {
        headerValue = headers[headerName];
        if (headerValue) {
          xhr.setRequestHeader(headerName, headerValue);
        }
      }
      formData = new FormData();
      if (this.options.params) {
        _ref1 = this.options.params;
        for (key in _ref1) {
          value = _ref1[key];
          formData.append(key, value);
        }
      }
      for (_j = 0, _len1 = files.length; _j < _len1; _j++) {
        file = files[_j];
        this.emit("sending", file, xhr, formData);
      }
      if (this.options.uploadMultiple) {
        this.emit("sendingmultiple", files, xhr, formData);
      }
      if (this.element.tagName === "FORM") {
        _ref2 = this.element.querySelectorAll("input, textarea, select, button");
        for (_k = 0, _len2 = _ref2.length; _k < _len2; _k++) {
          input = _ref2[_k];
          inputName = input.getAttribute("name");
          inputType = input.getAttribute("type");
          if (input.tagName === "SELECT" && input.hasAttribute("multiple")) {
            _ref3 = input.options;
            for (_l = 0, _len3 = _ref3.length; _l < _len3; _l++) {
              option = _ref3[_l];
              if (option.selected) {
                formData.append(inputName, option.value);
              }
            }
          } else if (!inputType || ((_ref4 = inputType.toLowerCase()) !== "checkbox" && _ref4 !== "radio") || input.checked) {
            formData.append(inputName, input.value);
          }
        }
      }
      for (i = _m = 0, _ref5 = files.length - 1; 0 <= _ref5 ? _m <= _ref5 : _m >= _ref5; i = 0 <= _ref5 ? ++_m : --_m) {
        formData.append(this._getParamName(i), files[i], this._renameFilename(files[i].name));
      }
      return this.submitRequest(xhr, formData, files);
    };

    Dropzone.prototype.submitRequest = function(xhr, formData, files) {
      return xhr.send(formData);
    };

    Dropzone.prototype._finished = function(files, responseText, e) {
      var file, _i, _len;
      for (_i = 0, _len = files.length; _i < _len; _i++) {
        file = files[_i];
        file.status = Dropzone.SUCCESS;
        this.emit("success", file, responseText, e);
        this.emit("complete", file);
      }
      if (this.options.uploadMultiple) {
        this.emit("successmultiple", files, responseText, e);
        this.emit("completemultiple", files);
      }
      if (this.options.autoProcessQueue) {
        return this.processQueue();
      }
    };

    Dropzone.prototype._errorProcessing = function(files, message, xhr) {
      var file, _i, _len;
      for (_i = 0, _len = files.length; _i < _len; _i++) {
        file = files[_i];
        file.status = Dropzone.ERROR;
        this.emit("error", file, message, xhr);
        this.emit("complete", file);
      }
      if (this.options.uploadMultiple) {
        this.emit("errormultiple", files, message, xhr);
        this.emit("completemultiple", files);
      }
      if (this.options.autoProcessQueue) {
        return this.processQueue();
      }
    };

    return Dropzone;

  })(Emitter);

  Dropzone.version = "4.3.0";

  Dropzone.options = {};

  Dropzone.optionsForElement = function(element) {
    if (element.getAttribute("id")) {
      return Dropzone.options[camelize(element.getAttribute("id"))];
    } else {
      return void 0;
    }
  };

  Dropzone.instances = [];

  Dropzone.forElement = function(element) {
    if (typeof element === "string") {
      element = document.querySelector(element);
    }
    if ((element != null ? element.dropzone : void 0) == null) {
      throw new Error("No Dropzone found for given element. This is probably because you're trying to access it before Dropzone had the time to initialize. Use the `init` option to setup any additional observers on your Dropzone.");
    }
    return element.dropzone;
  };

  Dropzone.autoDiscover = true;

  Dropzone.discover = function() {
    var checkElements, dropzone, dropzones, _i, _len, _results;
    if (document.querySelectorAll) {
      dropzones = document.querySelectorAll(".dropzone");
    } else {
      dropzones = [];
      checkElements = function(elements) {
        var el, _i, _len, _results;
        _results = [];
        for (_i = 0, _len = elements.length; _i < _len; _i++) {
          el = elements[_i];
          if (/(^| )dropzone($| )/.test(el.className)) {
            _results.push(dropzones.push(el));
          } else {
            _results.push(void 0);
          }
        }
        return _results;
      };
      checkElements(document.getElementsByTagName("div"));
      checkElements(document.getElementsByTagName("form"));
    }
    _results = [];
    for (_i = 0, _len = dropzones.length; _i < _len; _i++) {
      dropzone = dropzones[_i];
      if (Dropzone.optionsForElement(dropzone) !== false) {
        _results.push(new Dropzone(dropzone));
      } else {
        _results.push(void 0);
      }
    }
    return _results;
  };

  Dropzone.blacklistedBrowsers = [/opera.*Macintosh.*version\/12/i];

  Dropzone.isBrowserSupported = function() {
    var capableBrowser, regex, _i, _len, _ref;
    capableBrowser = true;
    if (window.File && window.FileReader && window.FileList && window.Blob && window.FormData && document.querySelector) {
      if (!("classList" in document.createElement("a"))) {
        capableBrowser = false;
      } else {
        _ref = Dropzone.blacklistedBrowsers;
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          regex = _ref[_i];
          if (regex.test(navigator.userAgent)) {
            capableBrowser = false;
            continue;
          }
        }
      }
    } else {
      capableBrowser = false;
    }
    return capableBrowser;
  };

  without = function(list, rejectedItem) {
    var item, _i, _len, _results;
    _results = [];
    for (_i = 0, _len = list.length; _i < _len; _i++) {
      item = list[_i];
      if (item !== rejectedItem) {
        _results.push(item);
      }
    }
    return _results;
  };

  camelize = function(str) {
    return str.replace(/[\-_](\w)/g, function(match) {
      return match.charAt(1).toUpperCase();
    });
  };

  Dropzone.createElement = function(string) {
    var div;
    div = document.createElement("div");
    div.innerHTML = string;
    return div.childNodes[0];
  };

  Dropzone.elementInside = function(element, container) {
    if (element === container) {
      return true;
    }
    while (element = element.parentNode) {
      if (element === container) {
        return true;
      }
    }
    return false;
  };

  Dropzone.getElement = function(el, name) {
    var element;
    if (typeof el === "string") {
      element = document.querySelector(el);
    } else if (el.nodeType != null) {
      element = el;
    }
    if (element == null) {
      throw new Error("Invalid `" + name + "` option provided. Please provide a CSS selector or a plain HTML element.");
    }
    return element;
  };

  Dropzone.getElements = function(els, name) {
    var e, el, elements, _i, _j, _len, _len1, _ref;
    if (els instanceof Array) {
      elements = [];
      try {
        for (_i = 0, _len = els.length; _i < _len; _i++) {
          el = els[_i];
          elements.push(this.getElement(el, name));
        }
      } catch (_error) {
        e = _error;
        elements = null;
      }
    } else if (typeof els === "string") {
      elements = [];
      _ref = document.querySelectorAll(els);
      for (_j = 0, _len1 = _ref.length; _j < _len1; _j++) {
        el = _ref[_j];
        elements.push(el);
      }
    } else if (els.nodeType != null) {
      elements = [els];
    }
    if (!((elements != null) && elements.length)) {
      throw new Error("Invalid `" + name + "` option provided. Please provide a CSS selector, a plain HTML element or a list of those.");
    }
    return elements;
  };

  Dropzone.confirm = function(question, accepted, rejected) {
    if (window.confirm(question)) {
      return accepted();
    } else if (rejected != null) {
      return rejected();
    }
  };

  Dropzone.isValidFile = function(file, acceptedFiles) {
    var baseMimeType, mimeType, validType, _i, _len;
    if (!acceptedFiles) {
      return true;
    }
    acceptedFiles = acceptedFiles.split(",");
    mimeType = file.type;
    baseMimeType = mimeType.replace(/\/.*$/, "");
    for (_i = 0, _len = acceptedFiles.length; _i < _len; _i++) {
      validType = acceptedFiles[_i];
      validType = validType.trim();
      if (validType.charAt(0) === ".") {
        if (file.name.toLowerCase().indexOf(validType.toLowerCase(), file.name.length - validType.length) !== -1) {
          return true;
        }
      } else if (/\/\*$/.test(validType)) {
        if (baseMimeType === validType.replace(/\/.*$/, "")) {
          return true;
        }
      } else {
        if (mimeType === validType) {
          return true;
        }
      }
    }
    return false;
  };

  if (typeof jQuery !== "undefined" && jQuery !== null) {
    jQuery.fn.dropzone = function(options) {
      return this.each(function() {
        return new Dropzone(this, options);
      });
    };
  }

  if (typeof module !== "undefined" && module !== null) {
    module.exports = Dropzone;
  } else {
    window.Dropzone = Dropzone;
  }

  Dropzone.ADDED = "added";

  Dropzone.QUEUED = "queued";

  Dropzone.ACCEPTED = Dropzone.QUEUED;

  Dropzone.UPLOADING = "uploading";

  Dropzone.PROCESSING = Dropzone.UPLOADING;

  Dropzone.CANCELED = "canceled";

  Dropzone.ERROR = "error";

  Dropzone.SUCCESS = "success";


  /*
  
  Bugfix for iOS 6 and 7
  Source: http://stackoverflow.com/questions/11929099/html5-canvas-drawimage-ratio-bug-ios
  based on the work of https://github.com/stomita/ios-imagefile-megapixel
   */

  detectVerticalSquash = function(img) {
    var alpha, canvas, ctx, data, ey, ih, iw, py, ratio, sy;
    iw = img.naturalWidth;
    ih = img.naturalHeight;
    canvas = document.createElement("canvas");
    canvas.width = 1;
    canvas.height = ih;
    ctx = canvas.getContext("2d");
    ctx.drawImage(img, 0, 0);
    data = ctx.getImageData(0, 0, 1, ih).data;
    sy = 0;
    ey = ih;
    py = ih;
    while (py > sy) {
      alpha = data[(py - 1) * 4 + 3];
      if (alpha === 0) {
        ey = py;
      } else {
        sy = py;
      }
      py = (ey + sy) >> 1;
    }
    ratio = py / ih;
    if (ratio === 0) {
      return 1;
    } else {
      return ratio;
    }
  };

  drawImageIOSFix = function(ctx, img, sx, sy, sw, sh, dx, dy, dw, dh) {
    var vertSquashRatio;
    vertSquashRatio = detectVerticalSquash(img);
    return ctx.drawImage(img, sx, sy, sw, sh, dx, dy, dw, dh / vertSquashRatio);
  };


  /*
   * contentloaded.js
   *
   * Author: Diego Perini (diego.perini at gmail.com)
   * Summary: cross-browser wrapper for DOMContentLoaded
   * Updated: 20101020
   * License: MIT
   * Version: 1.2
   *
   * URL:
   * http://javascript.nwbox.com/ContentLoaded/
   * http://javascript.nwbox.com/ContentLoaded/MIT-LICENSE
   */

  contentLoaded = function(win, fn) {
    var add, doc, done, init, poll, pre, rem, root, top;
    done = false;
    top = true;
    doc = win.document;
    root = doc.documentElement;
    add = (doc.addEventListener ? "addEventListener" : "attachEvent");
    rem = (doc.addEventListener ? "removeEventListener" : "detachEvent");
    pre = (doc.addEventListener ? "" : "on");
    init = function(e) {
      if (e.type === "readystatechange" && doc.readyState !== "complete") {
        return;
      }
      (e.type === "load" ? win : doc)[rem](pre + e.type, init, false);
      if (!done && (done = true)) {
        return fn.call(win, e.type || e);
      }
    };
    poll = function() {
      var e;
      try {
        root.doScroll("left");
      } catch (_error) {
        e = _error;
        setTimeout(poll, 50);
        return;
      }
      return init("poll");
    };
    if (doc.readyState !== "complete") {
      if (doc.createEventObject && root.doScroll) {
        try {
          top = !win.frameElement;
        } catch (_error) {}
        if (top) {
          poll();
        }
      }
      doc[add](pre + "DOMContentLoaded", init, false);
      doc[add](pre + "readystatechange", init, false);
      return win[add](pre + "load", init, false);
    }
  };

  Dropzone._autoDiscoverFunction = function() {
    if (Dropzone.autoDiscover) {
      return Dropzone.discover();
    }
  };

  contentLoaded(window, Dropzone._autoDiscoverFunction);

}).call(this);

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(10)(module)))

/***/ }),

/***/ 48:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(34);


/***/ })

},[48]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vKHdlYnBhY2spL2J1aWxkaW4vbW9kdWxlLmpzIiwid2VicGFjazovLy8uL25vZGVfbW9kdWxlcy9kcm9wem9uZS9kaXN0L2Ryb3B6b25lLmpzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiI7Ozs7O0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQTtBQUNBOzs7Ozs7Ozs7QUNwQkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0Esa0JBQWtCO0FBQ2xCLHlDQUF5QywwQkFBMEIsMkRBQTJELEVBQUUsa0JBQWtCLDBCQUEwQixFQUFFLG1DQUFtQyw4QkFBOEIsb0NBQW9DLGNBQWM7O0FBRWpTOztBQUVBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSw2Q0FBNkMsV0FBVztBQUN4RDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLCtDQUErQyxXQUFXO0FBQzFEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUEsR0FBRzs7QUFFSDtBQUNBOztBQUVBOztBQUVBOzs7QUFHQTtBQUNBOztBQUVBOztBQUVBLDZDQUE2QyxFQUFFO0FBQy9DOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxnQkFBZ0I7QUFDaEI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLDBDQUEwQyxVQUFVLHNCQUFzQixhQUFhO0FBQ3ZGO0FBQ0Esa0RBQWtELFlBQVk7QUFDOUQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esd0NBQXdDLFdBQVc7QUFDbkQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsV0FBVztBQUNYO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBLFdBQVc7QUFDWDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLE9BQU87O0FBRVA7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLDBDQUEwQyxXQUFXO0FBQ3JEO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsNENBQTRDLFlBQVk7QUFDeEQ7QUFDQTtBQUNBO0FBQ0E7QUFDQSwwR0FBMEc7QUFDMUc7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCLGVBQWU7QUFDZjtBQUNBO0FBQ0E7QUFDQSxtQkFBbUI7QUFDbkIsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsV0FBVztBQUNYO0FBQ0E7QUFDQSw0Q0FBNEMsWUFBWTtBQUN4RDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLDBDQUEwQyxXQUFXO0FBQ3JEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxXQUFXO0FBQ1g7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsMENBQTBDLFdBQVc7QUFDckQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLDBDQUEwQyxXQUFXO0FBQ3JEO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLHlDQUF5QyxXQUFXO0FBQ3BEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLDhCQUE4QixxRUFBcUU7QUFDbkc7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHNDQUFzQyxXQUFXO0FBQ2pEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esc0NBQXNDLFdBQVc7QUFDakQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxzQ0FBc0MsV0FBVztBQUNqRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHNDQUFzQyxXQUFXO0FBQ2pEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaURBQWlELFdBQVc7QUFDNUQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsdUNBQXVDLFdBQVc7QUFDbEQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsV0FBVztBQUNYO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esd0NBQXdDLFdBQVc7QUFDbkQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSw0Q0FBNEMsV0FBVztBQUN2RDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHNDQUFzQyxXQUFXO0FBQ2pEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHNDQUFzQyxXQUFXO0FBQ2pEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHNDQUFzQyxXQUFXO0FBQ2pEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7QUFDQSxzQ0FBc0MsV0FBVztBQUNqRDtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLDZDQUE2QyxXQUFXO0FBQ3hEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLHVDQUF1QyxXQUFXO0FBQ2xEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsdUNBQXVDLFdBQVc7QUFDbEQ7QUFDQTtBQUNBO0FBQ0E7QUFDQSxXQUFXO0FBQ1g7QUFDQSxXQUFXO0FBQ1g7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0EsV0FBVztBQUNYO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaURBQWlELFdBQVc7QUFDNUQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLG1CQUFtQjtBQUNuQixpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsV0FBVztBQUNYO0FBQ0EsT0FBTztBQUNQO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLDJEQUEyRCxVQUFVLDBEQUEwRCxhQUFhO0FBQzVJLE9BQU87QUFDUDtBQUNBLE9BQU87QUFDUCwwREFBMEQsVUFBVTtBQUNwRTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsV0FBVztBQUNYO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQOztBQUVBO0FBQ0E7QUFDQSx1Q0FBdUMsV0FBVztBQUNsRDtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxXQUFXO0FBQ1g7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBOztBQUVBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esc0NBQXNDLFdBQVc7QUFDakQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLHVDQUF1QyxXQUFXO0FBQ2xEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHdDQUF3QyxXQUFXO0FBQ25EO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGdEQUFnRCxXQUFXO0FBQzNEO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaURBQWlELFlBQVk7QUFDN0Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLHVDQUF1QyxXQUFXO0FBQ2xEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLDRDQUE0QyxZQUFZO0FBQ3hEO0FBQ0EsK0dBQStHLFlBQVk7QUFDM0g7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSw4Q0FBOEMsWUFBWTtBQUMxRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFdBQVc7QUFDWDtBQUNBO0FBQ0EsOENBQThDLFlBQVk7QUFDMUQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsNENBQTRDLFlBQVk7QUFDeEQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFdBQVc7QUFDWDtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esd0NBQXdDLFlBQVk7QUFDcEQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLDBDQUEwQyxZQUFZO0FBQ3REO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSw4Q0FBOEMsWUFBWTtBQUMxRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsV0FBVztBQUNYO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsZ0RBQWdELHdDQUF3QztBQUN4RjtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLHVDQUF1QyxXQUFXO0FBQ2xEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSx1Q0FBdUMsV0FBVztBQUNsRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQSxHQUFHOztBQUVIOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsNENBQTRDLFdBQVc7QUFDdkQ7QUFDQTtBQUNBO0FBQ0EsV0FBVztBQUNYO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHlDQUF5QyxXQUFXO0FBQ3BEO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBLHdDQUF3QyxXQUFXO0FBQ25EO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLG9DQUFvQyxXQUFXO0FBQy9DO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsdUNBQXVDLFdBQVc7QUFDbEQ7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0EsdUNBQXVDLFlBQVk7QUFDbkQ7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLDZDQUE2QyxXQUFXO0FBQ3hEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7O0FBRUE7O0FBRUE7O0FBRUE7O0FBRUE7O0FBRUE7O0FBRUE7O0FBRUE7O0FBRUE7OztBQUdBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBLENBQUMiLCJmaWxlIjoiL2pzL2Zyb250ZW5kL3ZlbmRvci5qcyIsInNvdXJjZXNDb250ZW50IjpbIm1vZHVsZS5leHBvcnRzID0gZnVuY3Rpb24obW9kdWxlKSB7XG5cdGlmKCFtb2R1bGUud2VicGFja1BvbHlmaWxsKSB7XG5cdFx0bW9kdWxlLmRlcHJlY2F0ZSA9IGZ1bmN0aW9uKCkge307XG5cdFx0bW9kdWxlLnBhdGhzID0gW107XG5cdFx0Ly8gbW9kdWxlLnBhcmVudCA9IHVuZGVmaW5lZCBieSBkZWZhdWx0XG5cdFx0aWYoIW1vZHVsZS5jaGlsZHJlbikgbW9kdWxlLmNoaWxkcmVuID0gW107XG5cdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KG1vZHVsZSwgXCJsb2FkZWRcIiwge1xuXHRcdFx0ZW51bWVyYWJsZTogdHJ1ZSxcblx0XHRcdGdldDogZnVuY3Rpb24oKSB7XG5cdFx0XHRcdHJldHVybiBtb2R1bGUubDtcblx0XHRcdH1cblx0XHR9KTtcblx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkobW9kdWxlLCBcImlkXCIsIHtcblx0XHRcdGVudW1lcmFibGU6IHRydWUsXG5cdFx0XHRnZXQ6IGZ1bmN0aW9uKCkge1xuXHRcdFx0XHRyZXR1cm4gbW9kdWxlLmk7XG5cdFx0XHR9XG5cdFx0fSk7XG5cdFx0bW9kdWxlLndlYnBhY2tQb2x5ZmlsbCA9IDE7XG5cdH1cblx0cmV0dXJuIG1vZHVsZTtcbn07XG5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAod2VicGFjaykvYnVpbGRpbi9tb2R1bGUuanNcbi8vIG1vZHVsZSBpZCA9IDEwXG4vLyBtb2R1bGUgY2h1bmtzID0gMiIsIlxuLypcbiAqXG4gKiBNb3JlIGluZm8gYXQgW3d3dy5kcm9wem9uZWpzLmNvbV0oaHR0cDovL3d3dy5kcm9wem9uZWpzLmNvbSlcbiAqXG4gKiBDb3B5cmlnaHQgKGMpIDIwMTIsIE1hdGlhcyBNZW5vXG4gKlxuICogUGVybWlzc2lvbiBpcyBoZXJlYnkgZ3JhbnRlZCwgZnJlZSBvZiBjaGFyZ2UsIHRvIGFueSBwZXJzb24gb2J0YWluaW5nIGEgY29weVxuICogb2YgdGhpcyBzb2Z0d2FyZSBhbmQgYXNzb2NpYXRlZCBkb2N1bWVudGF0aW9uIGZpbGVzICh0aGUgXCJTb2Z0d2FyZVwiKSwgdG8gZGVhbFxuICogaW4gdGhlIFNvZnR3YXJlIHdpdGhvdXQgcmVzdHJpY3Rpb24sIGluY2x1ZGluZyB3aXRob3V0IGxpbWl0YXRpb24gdGhlIHJpZ2h0c1xuICogdG8gdXNlLCBjb3B5LCBtb2RpZnksIG1lcmdlLCBwdWJsaXNoLCBkaXN0cmlidXRlLCBzdWJsaWNlbnNlLCBhbmQvb3Igc2VsbFxuICogY29waWVzIG9mIHRoZSBTb2Z0d2FyZSwgYW5kIHRvIHBlcm1pdCBwZXJzb25zIHRvIHdob20gdGhlIFNvZnR3YXJlIGlzXG4gKiBmdXJuaXNoZWQgdG8gZG8gc28sIHN1YmplY3QgdG8gdGhlIGZvbGxvd2luZyBjb25kaXRpb25zOlxuICpcbiAqIFRoZSBhYm92ZSBjb3B5cmlnaHQgbm90aWNlIGFuZCB0aGlzIHBlcm1pc3Npb24gbm90aWNlIHNoYWxsIGJlIGluY2x1ZGVkIGluXG4gKiBhbGwgY29waWVzIG9yIHN1YnN0YW50aWFsIHBvcnRpb25zIG9mIHRoZSBTb2Z0d2FyZS5cbiAqXG4gKiBUSEUgU09GVFdBUkUgSVMgUFJPVklERUQgXCJBUyBJU1wiLCBXSVRIT1VUIFdBUlJBTlRZIE9GIEFOWSBLSU5ELCBFWFBSRVNTIE9SXG4gKiBJTVBMSUVELCBJTkNMVURJTkcgQlVUIE5PVCBMSU1JVEVEIFRPIFRIRSBXQVJSQU5USUVTIE9GIE1FUkNIQU5UQUJJTElUWSxcbiAqIEZJVE5FU1MgRk9SIEEgUEFSVElDVUxBUiBQVVJQT1NFIEFORCBOT05JTkZSSU5HRU1FTlQuIElOIE5PIEVWRU5UIFNIQUxMIFRIRVxuICogQVVUSE9SUyBPUiBDT1BZUklHSFQgSE9MREVSUyBCRSBMSUFCTEUgRk9SIEFOWSBDTEFJTSwgREFNQUdFUyBPUiBPVEhFUlxuICogTElBQklMSVRZLCBXSEVUSEVSIElOIEFOIEFDVElPTiBPRiBDT05UUkFDVCwgVE9SVCBPUiBPVEhFUldJU0UsIEFSSVNJTkcgRlJPTSxcbiAqIE9VVCBPRiBPUiBJTiBDT05ORUNUSU9OIFdJVEggVEhFIFNPRlRXQVJFIE9SIFRIRSBVU0UgT1IgT1RIRVIgREVBTElOR1MgSU5cbiAqIFRIRSBTT0ZUV0FSRS5cbiAqXG4gKi9cblxuKGZ1bmN0aW9uKCkge1xuICB2YXIgRHJvcHpvbmUsIEVtaXR0ZXIsIGNhbWVsaXplLCBjb250ZW50TG9hZGVkLCBkZXRlY3RWZXJ0aWNhbFNxdWFzaCwgZHJhd0ltYWdlSU9TRml4LCBub29wLCB3aXRob3V0LFxuICAgIF9fc2xpY2UgPSBbXS5zbGljZSxcbiAgICBfX2hhc1Byb3AgPSB7fS5oYXNPd25Qcm9wZXJ0eSxcbiAgICBfX2V4dGVuZHMgPSBmdW5jdGlvbihjaGlsZCwgcGFyZW50KSB7IGZvciAodmFyIGtleSBpbiBwYXJlbnQpIHsgaWYgKF9faGFzUHJvcC5jYWxsKHBhcmVudCwga2V5KSkgY2hpbGRba2V5XSA9IHBhcmVudFtrZXldOyB9IGZ1bmN0aW9uIGN0b3IoKSB7IHRoaXMuY29uc3RydWN0b3IgPSBjaGlsZDsgfSBjdG9yLnByb3RvdHlwZSA9IHBhcmVudC5wcm90b3R5cGU7IGNoaWxkLnByb3RvdHlwZSA9IG5ldyBjdG9yKCk7IGNoaWxkLl9fc3VwZXJfXyA9IHBhcmVudC5wcm90b3R5cGU7IHJldHVybiBjaGlsZDsgfTtcblxuICBub29wID0gZnVuY3Rpb24oKSB7fTtcblxuICBFbWl0dGVyID0gKGZ1bmN0aW9uKCkge1xuICAgIGZ1bmN0aW9uIEVtaXR0ZXIoKSB7fVxuXG4gICAgRW1pdHRlci5wcm90b3R5cGUuYWRkRXZlbnRMaXN0ZW5lciA9IEVtaXR0ZXIucHJvdG90eXBlLm9uO1xuXG4gICAgRW1pdHRlci5wcm90b3R5cGUub24gPSBmdW5jdGlvbihldmVudCwgZm4pIHtcbiAgICAgIHRoaXMuX2NhbGxiYWNrcyA9IHRoaXMuX2NhbGxiYWNrcyB8fCB7fTtcbiAgICAgIGlmICghdGhpcy5fY2FsbGJhY2tzW2V2ZW50XSkge1xuICAgICAgICB0aGlzLl9jYWxsYmFja3NbZXZlbnRdID0gW107XG4gICAgICB9XG4gICAgICB0aGlzLl9jYWxsYmFja3NbZXZlbnRdLnB1c2goZm4pO1xuICAgICAgcmV0dXJuIHRoaXM7XG4gICAgfTtcblxuICAgIEVtaXR0ZXIucHJvdG90eXBlLmVtaXQgPSBmdW5jdGlvbigpIHtcbiAgICAgIHZhciBhcmdzLCBjYWxsYmFjaywgY2FsbGJhY2tzLCBldmVudCwgX2ksIF9sZW47XG4gICAgICBldmVudCA9IGFyZ3VtZW50c1swXSwgYXJncyA9IDIgPD0gYXJndW1lbnRzLmxlbmd0aCA/IF9fc2xpY2UuY2FsbChhcmd1bWVudHMsIDEpIDogW107XG4gICAgICB0aGlzLl9jYWxsYmFja3MgPSB0aGlzLl9jYWxsYmFja3MgfHwge307XG4gICAgICBjYWxsYmFja3MgPSB0aGlzLl9jYWxsYmFja3NbZXZlbnRdO1xuICAgICAgaWYgKGNhbGxiYWNrcykge1xuICAgICAgICBmb3IgKF9pID0gMCwgX2xlbiA9IGNhbGxiYWNrcy5sZW5ndGg7IF9pIDwgX2xlbjsgX2krKykge1xuICAgICAgICAgIGNhbGxiYWNrID0gY2FsbGJhY2tzW19pXTtcbiAgICAgICAgICBjYWxsYmFjay5hcHBseSh0aGlzLCBhcmdzKTtcbiAgICAgICAgfVxuICAgICAgfVxuICAgICAgcmV0dXJuIHRoaXM7XG4gICAgfTtcblxuICAgIEVtaXR0ZXIucHJvdG90eXBlLnJlbW92ZUxpc3RlbmVyID0gRW1pdHRlci5wcm90b3R5cGUub2ZmO1xuXG4gICAgRW1pdHRlci5wcm90b3R5cGUucmVtb3ZlQWxsTGlzdGVuZXJzID0gRW1pdHRlci5wcm90b3R5cGUub2ZmO1xuXG4gICAgRW1pdHRlci5wcm90b3R5cGUucmVtb3ZlRXZlbnRMaXN0ZW5lciA9IEVtaXR0ZXIucHJvdG90eXBlLm9mZjtcblxuICAgIEVtaXR0ZXIucHJvdG90eXBlLm9mZiA9IGZ1bmN0aW9uKGV2ZW50LCBmbikge1xuICAgICAgdmFyIGNhbGxiYWNrLCBjYWxsYmFja3MsIGksIF9pLCBfbGVuO1xuICAgICAgaWYgKCF0aGlzLl9jYWxsYmFja3MgfHwgYXJndW1lbnRzLmxlbmd0aCA9PT0gMCkge1xuICAgICAgICB0aGlzLl9jYWxsYmFja3MgPSB7fTtcbiAgICAgICAgcmV0dXJuIHRoaXM7XG4gICAgICB9XG4gICAgICBjYWxsYmFja3MgPSB0aGlzLl9jYWxsYmFja3NbZXZlbnRdO1xuICAgICAgaWYgKCFjYWxsYmFja3MpIHtcbiAgICAgICAgcmV0dXJuIHRoaXM7XG4gICAgICB9XG4gICAgICBpZiAoYXJndW1lbnRzLmxlbmd0aCA9PT0gMSkge1xuICAgICAgICBkZWxldGUgdGhpcy5fY2FsbGJhY2tzW2V2ZW50XTtcbiAgICAgICAgcmV0dXJuIHRoaXM7XG4gICAgICB9XG4gICAgICBmb3IgKGkgPSBfaSA9IDAsIF9sZW4gPSBjYWxsYmFja3MubGVuZ3RoOyBfaSA8IF9sZW47IGkgPSArK19pKSB7XG4gICAgICAgIGNhbGxiYWNrID0gY2FsbGJhY2tzW2ldO1xuICAgICAgICBpZiAoY2FsbGJhY2sgPT09IGZuKSB7XG4gICAgICAgICAgY2FsbGJhY2tzLnNwbGljZShpLCAxKTtcbiAgICAgICAgICBicmVhaztcbiAgICAgICAgfVxuICAgICAgfVxuICAgICAgcmV0dXJuIHRoaXM7XG4gICAgfTtcblxuICAgIHJldHVybiBFbWl0dGVyO1xuXG4gIH0pKCk7XG5cbiAgRHJvcHpvbmUgPSAoZnVuY3Rpb24oX3N1cGVyKSB7XG4gICAgdmFyIGV4dGVuZCwgcmVzb2x2ZU9wdGlvbjtcblxuICAgIF9fZXh0ZW5kcyhEcm9wem9uZSwgX3N1cGVyKTtcblxuICAgIERyb3B6b25lLnByb3RvdHlwZS5FbWl0dGVyID0gRW1pdHRlcjtcblxuXG4gICAgLypcbiAgICBUaGlzIGlzIGEgbGlzdCBvZiBhbGwgYXZhaWxhYmxlIGV2ZW50cyB5b3UgY2FuIHJlZ2lzdGVyIG9uIGEgZHJvcHpvbmUgb2JqZWN0LlxuICAgIFxuICAgIFlvdSBjYW4gcmVnaXN0ZXIgYW4gZXZlbnQgaGFuZGxlciBsaWtlIHRoaXM6XG4gICAgXG4gICAgICAgIGRyb3B6b25lLm9uKFwiZHJhZ0VudGVyXCIsIGZ1bmN0aW9uKCkgeyB9KTtcbiAgICAgKi9cblxuICAgIERyb3B6b25lLnByb3RvdHlwZS5ldmVudHMgPSBbXCJkcm9wXCIsIFwiZHJhZ3N0YXJ0XCIsIFwiZHJhZ2VuZFwiLCBcImRyYWdlbnRlclwiLCBcImRyYWdvdmVyXCIsIFwiZHJhZ2xlYXZlXCIsIFwiYWRkZWRmaWxlXCIsIFwiYWRkZWRmaWxlc1wiLCBcInJlbW92ZWRmaWxlXCIsIFwidGh1bWJuYWlsXCIsIFwiZXJyb3JcIiwgXCJlcnJvcm11bHRpcGxlXCIsIFwicHJvY2Vzc2luZ1wiLCBcInByb2Nlc3NpbmdtdWx0aXBsZVwiLCBcInVwbG9hZHByb2dyZXNzXCIsIFwidG90YWx1cGxvYWRwcm9ncmVzc1wiLCBcInNlbmRpbmdcIiwgXCJzZW5kaW5nbXVsdGlwbGVcIiwgXCJzdWNjZXNzXCIsIFwic3VjY2Vzc211bHRpcGxlXCIsIFwiY2FuY2VsZWRcIiwgXCJjYW5jZWxlZG11bHRpcGxlXCIsIFwiY29tcGxldGVcIiwgXCJjb21wbGV0ZW11bHRpcGxlXCIsIFwicmVzZXRcIiwgXCJtYXhmaWxlc2V4Y2VlZGVkXCIsIFwibWF4ZmlsZXNyZWFjaGVkXCIsIFwicXVldWVjb21wbGV0ZVwiXTtcblxuICAgIERyb3B6b25lLnByb3RvdHlwZS5kZWZhdWx0T3B0aW9ucyA9IHtcbiAgICAgIHVybDogbnVsbCxcbiAgICAgIG1ldGhvZDogXCJwb3N0XCIsXG4gICAgICB3aXRoQ3JlZGVudGlhbHM6IGZhbHNlLFxuICAgICAgcGFyYWxsZWxVcGxvYWRzOiAyLFxuICAgICAgdXBsb2FkTXVsdGlwbGU6IGZhbHNlLFxuICAgICAgbWF4RmlsZXNpemU6IDI1NixcbiAgICAgIHBhcmFtTmFtZTogXCJmaWxlXCIsXG4gICAgICBjcmVhdGVJbWFnZVRodW1ibmFpbHM6IHRydWUsXG4gICAgICBtYXhUaHVtYm5haWxGaWxlc2l6ZTogMTAsXG4gICAgICB0aHVtYm5haWxXaWR0aDogMTIwLFxuICAgICAgdGh1bWJuYWlsSGVpZ2h0OiAxMjAsXG4gICAgICBmaWxlc2l6ZUJhc2U6IDEwMDAsXG4gICAgICBtYXhGaWxlczogbnVsbCxcbiAgICAgIHBhcmFtczoge30sXG4gICAgICBjbGlja2FibGU6IHRydWUsXG4gICAgICBpZ25vcmVIaWRkZW5GaWxlczogdHJ1ZSxcbiAgICAgIGFjY2VwdGVkRmlsZXM6IG51bGwsXG4gICAgICBhY2NlcHRlZE1pbWVUeXBlczogbnVsbCxcbiAgICAgIGF1dG9Qcm9jZXNzUXVldWU6IHRydWUsXG4gICAgICBhdXRvUXVldWU6IHRydWUsXG4gICAgICBhZGRSZW1vdmVMaW5rczogZmFsc2UsXG4gICAgICBwcmV2aWV3c0NvbnRhaW5lcjogbnVsbCxcbiAgICAgIGhpZGRlbklucHV0Q29udGFpbmVyOiBcImJvZHlcIixcbiAgICAgIGNhcHR1cmU6IG51bGwsXG4gICAgICByZW5hbWVGaWxlbmFtZTogbnVsbCxcbiAgICAgIGRpY3REZWZhdWx0TWVzc2FnZTogXCJEcm9wIGZpbGVzIGhlcmUgdG8gdXBsb2FkXCIsXG4gICAgICBkaWN0RmFsbGJhY2tNZXNzYWdlOiBcIllvdXIgYnJvd3NlciBkb2VzIG5vdCBzdXBwb3J0IGRyYWcnbidkcm9wIGZpbGUgdXBsb2Fkcy5cIixcbiAgICAgIGRpY3RGYWxsYmFja1RleHQ6IFwiUGxlYXNlIHVzZSB0aGUgZmFsbGJhY2sgZm9ybSBiZWxvdyB0byB1cGxvYWQgeW91ciBmaWxlcyBsaWtlIGluIHRoZSBvbGRlbiBkYXlzLlwiLFxuICAgICAgZGljdEZpbGVUb29CaWc6IFwiRmlsZSBpcyB0b28gYmlnICh7e2ZpbGVzaXplfX1NaUIpLiBNYXggZmlsZXNpemU6IHt7bWF4RmlsZXNpemV9fU1pQi5cIixcbiAgICAgIGRpY3RJbnZhbGlkRmlsZVR5cGU6IFwiWW91IGNhbid0IHVwbG9hZCBmaWxlcyBvZiB0aGlzIHR5cGUuXCIsXG4gICAgICBkaWN0UmVzcG9uc2VFcnJvcjogXCJTZXJ2ZXIgcmVzcG9uZGVkIHdpdGgge3tzdGF0dXNDb2RlfX0gY29kZS5cIixcbiAgICAgIGRpY3RDYW5jZWxVcGxvYWQ6IFwiQ2FuY2VsIHVwbG9hZFwiLFxuICAgICAgZGljdENhbmNlbFVwbG9hZENvbmZpcm1hdGlvbjogXCJBcmUgeW91IHN1cmUgeW91IHdhbnQgdG8gY2FuY2VsIHRoaXMgdXBsb2FkP1wiLFxuICAgICAgZGljdFJlbW92ZUZpbGU6IFwiUmVtb3ZlIGZpbGVcIixcbiAgICAgIGRpY3RSZW1vdmVGaWxlQ29uZmlybWF0aW9uOiBudWxsLFxuICAgICAgZGljdE1heEZpbGVzRXhjZWVkZWQ6IFwiWW91IGNhbiBub3QgdXBsb2FkIGFueSBtb3JlIGZpbGVzLlwiLFxuICAgICAgYWNjZXB0OiBmdW5jdGlvbihmaWxlLCBkb25lKSB7XG4gICAgICAgIHJldHVybiBkb25lKCk7XG4gICAgICB9LFxuICAgICAgaW5pdDogZnVuY3Rpb24oKSB7XG4gICAgICAgIHJldHVybiBub29wO1xuICAgICAgfSxcbiAgICAgIGZvcmNlRmFsbGJhY2s6IGZhbHNlLFxuICAgICAgZmFsbGJhY2s6IGZ1bmN0aW9uKCkge1xuICAgICAgICB2YXIgY2hpbGQsIG1lc3NhZ2VFbGVtZW50LCBzcGFuLCBfaSwgX2xlbiwgX3JlZjtcbiAgICAgICAgdGhpcy5lbGVtZW50LmNsYXNzTmFtZSA9IFwiXCIgKyB0aGlzLmVsZW1lbnQuY2xhc3NOYW1lICsgXCIgZHotYnJvd3Nlci1ub3Qtc3VwcG9ydGVkXCI7XG4gICAgICAgIF9yZWYgPSB0aGlzLmVsZW1lbnQuZ2V0RWxlbWVudHNCeVRhZ05hbWUoXCJkaXZcIik7XG4gICAgICAgIGZvciAoX2kgPSAwLCBfbGVuID0gX3JlZi5sZW5ndGg7IF9pIDwgX2xlbjsgX2krKykge1xuICAgICAgICAgIGNoaWxkID0gX3JlZltfaV07XG4gICAgICAgICAgaWYgKC8oXnwgKWR6LW1lc3NhZ2UoJHwgKS8udGVzdChjaGlsZC5jbGFzc05hbWUpKSB7XG4gICAgICAgICAgICBtZXNzYWdlRWxlbWVudCA9IGNoaWxkO1xuICAgICAgICAgICAgY2hpbGQuY2xhc3NOYW1lID0gXCJkei1tZXNzYWdlXCI7XG4gICAgICAgICAgICBjb250aW51ZTtcbiAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgaWYgKCFtZXNzYWdlRWxlbWVudCkge1xuICAgICAgICAgIG1lc3NhZ2VFbGVtZW50ID0gRHJvcHpvbmUuY3JlYXRlRWxlbWVudChcIjxkaXYgY2xhc3M9XFxcImR6LW1lc3NhZ2VcXFwiPjxzcGFuPjwvc3Bhbj48L2Rpdj5cIik7XG4gICAgICAgICAgdGhpcy5lbGVtZW50LmFwcGVuZENoaWxkKG1lc3NhZ2VFbGVtZW50KTtcbiAgICAgICAgfVxuICAgICAgICBzcGFuID0gbWVzc2FnZUVsZW1lbnQuZ2V0RWxlbWVudHNCeVRhZ05hbWUoXCJzcGFuXCIpWzBdO1xuICAgICAgICBpZiAoc3Bhbikge1xuICAgICAgICAgIGlmIChzcGFuLnRleHRDb250ZW50ICE9IG51bGwpIHtcbiAgICAgICAgICAgIHNwYW4udGV4dENvbnRlbnQgPSB0aGlzLm9wdGlvbnMuZGljdEZhbGxiYWNrTWVzc2FnZTtcbiAgICAgICAgICB9IGVsc2UgaWYgKHNwYW4uaW5uZXJUZXh0ICE9IG51bGwpIHtcbiAgICAgICAgICAgIHNwYW4uaW5uZXJUZXh0ID0gdGhpcy5vcHRpb25zLmRpY3RGYWxsYmFja01lc3NhZ2U7XG4gICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIHJldHVybiB0aGlzLmVsZW1lbnQuYXBwZW5kQ2hpbGQodGhpcy5nZXRGYWxsYmFja0Zvcm0oKSk7XG4gICAgICB9LFxuICAgICAgcmVzaXplOiBmdW5jdGlvbihmaWxlKSB7XG4gICAgICAgIHZhciBpbmZvLCBzcmNSYXRpbywgdHJnUmF0aW87XG4gICAgICAgIGluZm8gPSB7XG4gICAgICAgICAgc3JjWDogMCxcbiAgICAgICAgICBzcmNZOiAwLFxuICAgICAgICAgIHNyY1dpZHRoOiBmaWxlLndpZHRoLFxuICAgICAgICAgIHNyY0hlaWdodDogZmlsZS5oZWlnaHRcbiAgICAgICAgfTtcbiAgICAgICAgc3JjUmF0aW8gPSBmaWxlLndpZHRoIC8gZmlsZS5oZWlnaHQ7XG4gICAgICAgIGluZm8ub3B0V2lkdGggPSB0aGlzLm9wdGlvbnMudGh1bWJuYWlsV2lkdGg7XG4gICAgICAgIGluZm8ub3B0SGVpZ2h0ID0gdGhpcy5vcHRpb25zLnRodW1ibmFpbEhlaWdodDtcbiAgICAgICAgaWYgKChpbmZvLm9wdFdpZHRoID09IG51bGwpICYmIChpbmZvLm9wdEhlaWdodCA9PSBudWxsKSkge1xuICAgICAgICAgIGluZm8ub3B0V2lkdGggPSBpbmZvLnNyY1dpZHRoO1xuICAgICAgICAgIGluZm8ub3B0SGVpZ2h0ID0gaW5mby5zcmNIZWlnaHQ7XG4gICAgICAgIH0gZWxzZSBpZiAoaW5mby5vcHRXaWR0aCA9PSBudWxsKSB7XG4gICAgICAgICAgaW5mby5vcHRXaWR0aCA9IHNyY1JhdGlvICogaW5mby5vcHRIZWlnaHQ7XG4gICAgICAgIH0gZWxzZSBpZiAoaW5mby5vcHRIZWlnaHQgPT0gbnVsbCkge1xuICAgICAgICAgIGluZm8ub3B0SGVpZ2h0ID0gKDEgLyBzcmNSYXRpbykgKiBpbmZvLm9wdFdpZHRoO1xuICAgICAgICB9XG4gICAgICAgIHRyZ1JhdGlvID0gaW5mby5vcHRXaWR0aCAvIGluZm8ub3B0SGVpZ2h0O1xuICAgICAgICBpZiAoZmlsZS5oZWlnaHQgPCBpbmZvLm9wdEhlaWdodCB8fCBmaWxlLndpZHRoIDwgaW5mby5vcHRXaWR0aCkge1xuICAgICAgICAgIGluZm8udHJnSGVpZ2h0ID0gaW5mby5zcmNIZWlnaHQ7XG4gICAgICAgICAgaW5mby50cmdXaWR0aCA9IGluZm8uc3JjV2lkdGg7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgaWYgKHNyY1JhdGlvID4gdHJnUmF0aW8pIHtcbiAgICAgICAgICAgIGluZm8uc3JjSGVpZ2h0ID0gZmlsZS5oZWlnaHQ7XG4gICAgICAgICAgICBpbmZvLnNyY1dpZHRoID0gaW5mby5zcmNIZWlnaHQgKiB0cmdSYXRpbztcbiAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgaW5mby5zcmNXaWR0aCA9IGZpbGUud2lkdGg7XG4gICAgICAgICAgICBpbmZvLnNyY0hlaWdodCA9IGluZm8uc3JjV2lkdGggLyB0cmdSYXRpbztcbiAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgaW5mby5zcmNYID0gKGZpbGUud2lkdGggLSBpbmZvLnNyY1dpZHRoKSAvIDI7XG4gICAgICAgIGluZm8uc3JjWSA9IChmaWxlLmhlaWdodCAtIGluZm8uc3JjSGVpZ2h0KSAvIDI7XG4gICAgICAgIHJldHVybiBpbmZvO1xuICAgICAgfSxcblxuICAgICAgLypcbiAgICAgIFRob3NlIGZ1bmN0aW9ucyByZWdpc3RlciB0aGVtc2VsdmVzIHRvIHRoZSBldmVudHMgb24gaW5pdCBhbmQgaGFuZGxlIGFsbFxuICAgICAgdGhlIHVzZXIgaW50ZXJmYWNlIHNwZWNpZmljIHN0dWZmLiBPdmVyd3JpdGluZyB0aGVtIHdvbid0IGJyZWFrIHRoZSB1cGxvYWRcbiAgICAgIGJ1dCBjYW4gYnJlYWsgdGhlIHdheSBpdCdzIGRpc3BsYXllZC5cbiAgICAgIFlvdSBjYW4gb3ZlcndyaXRlIHRoZW0gaWYgeW91IGRvbid0IGxpa2UgdGhlIGRlZmF1bHQgYmVoYXZpb3IuIElmIHlvdSBqdXN0XG4gICAgICB3YW50IHRvIGFkZCBhbiBhZGRpdGlvbmFsIGV2ZW50IGhhbmRsZXIsIHJlZ2lzdGVyIGl0IG9uIHRoZSBkcm9wem9uZSBvYmplY3RcbiAgICAgIGFuZCBkb24ndCBvdmVyd3JpdGUgdGhvc2Ugb3B0aW9ucy5cbiAgICAgICAqL1xuICAgICAgZHJvcDogZnVuY3Rpb24oZSkge1xuICAgICAgICByZXR1cm4gdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoXCJkei1kcmFnLWhvdmVyXCIpO1xuICAgICAgfSxcbiAgICAgIGRyYWdzdGFydDogbm9vcCxcbiAgICAgIGRyYWdlbmQ6IGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKFwiZHotZHJhZy1ob3ZlclwiKTtcbiAgICAgIH0sXG4gICAgICBkcmFnZW50ZXI6IGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QuYWRkKFwiZHotZHJhZy1ob3ZlclwiKTtcbiAgICAgIH0sXG4gICAgICBkcmFnb3ZlcjogZnVuY3Rpb24oZSkge1xuICAgICAgICByZXR1cm4gdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoXCJkei1kcmFnLWhvdmVyXCIpO1xuICAgICAgfSxcbiAgICAgIGRyYWdsZWF2ZTogZnVuY3Rpb24oZSkge1xuICAgICAgICByZXR1cm4gdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoXCJkei1kcmFnLWhvdmVyXCIpO1xuICAgICAgfSxcbiAgICAgIHBhc3RlOiBub29wLFxuICAgICAgcmVzZXQ6IGZ1bmN0aW9uKCkge1xuICAgICAgICByZXR1cm4gdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoXCJkei1zdGFydGVkXCIpO1xuICAgICAgfSxcbiAgICAgIGFkZGVkZmlsZTogZnVuY3Rpb24oZmlsZSkge1xuICAgICAgICB2YXIgbm9kZSwgcmVtb3ZlRmlsZUV2ZW50LCByZW1vdmVMaW5rLCBfaSwgX2osIF9rLCBfbGVuLCBfbGVuMSwgX2xlbjIsIF9yZWYsIF9yZWYxLCBfcmVmMiwgX3Jlc3VsdHM7XG4gICAgICAgIGlmICh0aGlzLmVsZW1lbnQgPT09IHRoaXMucHJldmlld3NDb250YWluZXIpIHtcbiAgICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmFkZChcImR6LXN0YXJ0ZWRcIik7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKHRoaXMucHJldmlld3NDb250YWluZXIpIHtcbiAgICAgICAgICBmaWxlLnByZXZpZXdFbGVtZW50ID0gRHJvcHpvbmUuY3JlYXRlRWxlbWVudCh0aGlzLm9wdGlvbnMucHJldmlld1RlbXBsYXRlLnRyaW0oKSk7XG4gICAgICAgICAgZmlsZS5wcmV2aWV3VGVtcGxhdGUgPSBmaWxlLnByZXZpZXdFbGVtZW50O1xuICAgICAgICAgIHRoaXMucHJldmlld3NDb250YWluZXIuYXBwZW5kQ2hpbGQoZmlsZS5wcmV2aWV3RWxlbWVudCk7XG4gICAgICAgICAgX3JlZiA9IGZpbGUucHJldmlld0VsZW1lbnQucXVlcnlTZWxlY3RvckFsbChcIltkYXRhLWR6LW5hbWVdXCIpO1xuICAgICAgICAgIGZvciAoX2kgPSAwLCBfbGVuID0gX3JlZi5sZW5ndGg7IF9pIDwgX2xlbjsgX2krKykge1xuICAgICAgICAgICAgbm9kZSA9IF9yZWZbX2ldO1xuICAgICAgICAgICAgbm9kZS50ZXh0Q29udGVudCA9IHRoaXMuX3JlbmFtZUZpbGVuYW1lKGZpbGUubmFtZSk7XG4gICAgICAgICAgfVxuICAgICAgICAgIF9yZWYxID0gZmlsZS5wcmV2aWV3RWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKFwiW2RhdGEtZHotc2l6ZV1cIik7XG4gICAgICAgICAgZm9yIChfaiA9IDAsIF9sZW4xID0gX3JlZjEubGVuZ3RoOyBfaiA8IF9sZW4xOyBfaisrKSB7XG4gICAgICAgICAgICBub2RlID0gX3JlZjFbX2pdO1xuICAgICAgICAgICAgbm9kZS5pbm5lckhUTUwgPSB0aGlzLmZpbGVzaXplKGZpbGUuc2l6ZSk7XG4gICAgICAgICAgfVxuICAgICAgICAgIGlmICh0aGlzLm9wdGlvbnMuYWRkUmVtb3ZlTGlua3MpIHtcbiAgICAgICAgICAgIGZpbGUuX3JlbW92ZUxpbmsgPSBEcm9wem9uZS5jcmVhdGVFbGVtZW50KFwiPGEgY2xhc3M9XFxcImR6LXJlbW92ZVxcXCIgaHJlZj1cXFwiamF2YXNjcmlwdDp1bmRlZmluZWQ7XFxcIiBkYXRhLWR6LXJlbW92ZT5cIiArIHRoaXMub3B0aW9ucy5kaWN0UmVtb3ZlRmlsZSArIFwiPC9hPlwiKTtcbiAgICAgICAgICAgIGZpbGUucHJldmlld0VsZW1lbnQuYXBwZW5kQ2hpbGQoZmlsZS5fcmVtb3ZlTGluayk7XG4gICAgICAgICAgfVxuICAgICAgICAgIHJlbW92ZUZpbGVFdmVudCA9IChmdW5jdGlvbihfdGhpcykge1xuICAgICAgICAgICAgcmV0dXJuIGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgICBlLnN0b3BQcm9wYWdhdGlvbigpO1xuICAgICAgICAgICAgICBpZiAoZmlsZS5zdGF0dXMgPT09IERyb3B6b25lLlVQTE9BRElORykge1xuICAgICAgICAgICAgICAgIHJldHVybiBEcm9wem9uZS5jb25maXJtKF90aGlzLm9wdGlvbnMuZGljdENhbmNlbFVwbG9hZENvbmZpcm1hdGlvbiwgZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICByZXR1cm4gX3RoaXMucmVtb3ZlRmlsZShmaWxlKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICBpZiAoX3RoaXMub3B0aW9ucy5kaWN0UmVtb3ZlRmlsZUNvbmZpcm1hdGlvbikge1xuICAgICAgICAgICAgICAgICAgcmV0dXJuIERyb3B6b25lLmNvbmZpcm0oX3RoaXMub3B0aW9ucy5kaWN0UmVtb3ZlRmlsZUNvbmZpcm1hdGlvbiwgZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBfdGhpcy5yZW1vdmVGaWxlKGZpbGUpO1xuICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgIHJldHVybiBfdGhpcy5yZW1vdmVGaWxlKGZpbGUpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfTtcbiAgICAgICAgICB9KSh0aGlzKTtcbiAgICAgICAgICBfcmVmMiA9IGZpbGUucHJldmlld0VsZW1lbnQucXVlcnlTZWxlY3RvckFsbChcIltkYXRhLWR6LXJlbW92ZV1cIik7XG4gICAgICAgICAgX3Jlc3VsdHMgPSBbXTtcbiAgICAgICAgICBmb3IgKF9rID0gMCwgX2xlbjIgPSBfcmVmMi5sZW5ndGg7IF9rIDwgX2xlbjI7IF9rKyspIHtcbiAgICAgICAgICAgIHJlbW92ZUxpbmsgPSBfcmVmMltfa107XG4gICAgICAgICAgICBfcmVzdWx0cy5wdXNoKHJlbW92ZUxpbmsuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIHJlbW92ZUZpbGVFdmVudCkpO1xuICAgICAgICAgIH1cbiAgICAgICAgICByZXR1cm4gX3Jlc3VsdHM7XG4gICAgICAgIH1cbiAgICAgIH0sXG4gICAgICByZW1vdmVkZmlsZTogZnVuY3Rpb24oZmlsZSkge1xuICAgICAgICB2YXIgX3JlZjtcbiAgICAgICAgaWYgKGZpbGUucHJldmlld0VsZW1lbnQpIHtcbiAgICAgICAgICBpZiAoKF9yZWYgPSBmaWxlLnByZXZpZXdFbGVtZW50KSAhPSBudWxsKSB7XG4gICAgICAgICAgICBfcmVmLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQoZmlsZS5wcmV2aWV3RWxlbWVudCk7XG4gICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIHJldHVybiB0aGlzLl91cGRhdGVNYXhGaWxlc1JlYWNoZWRDbGFzcygpO1xuICAgICAgfSxcbiAgICAgIHRodW1ibmFpbDogZnVuY3Rpb24oZmlsZSwgZGF0YVVybCkge1xuICAgICAgICB2YXIgdGh1bWJuYWlsRWxlbWVudCwgX2ksIF9sZW4sIF9yZWY7XG4gICAgICAgIGlmIChmaWxlLnByZXZpZXdFbGVtZW50KSB7XG4gICAgICAgICAgZmlsZS5wcmV2aWV3RWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKFwiZHotZmlsZS1wcmV2aWV3XCIpO1xuICAgICAgICAgIF9yZWYgPSBmaWxlLnByZXZpZXdFbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoXCJbZGF0YS1kei10aHVtYm5haWxdXCIpO1xuICAgICAgICAgIGZvciAoX2kgPSAwLCBfbGVuID0gX3JlZi5sZW5ndGg7IF9pIDwgX2xlbjsgX2krKykge1xuICAgICAgICAgICAgdGh1bWJuYWlsRWxlbWVudCA9IF9yZWZbX2ldO1xuICAgICAgICAgICAgdGh1bWJuYWlsRWxlbWVudC5hbHQgPSBmaWxlLm5hbWU7XG4gICAgICAgICAgICB0aHVtYm5haWxFbGVtZW50LnNyYyA9IGRhdGFVcmw7XG4gICAgICAgICAgfVxuICAgICAgICAgIHJldHVybiBzZXRUaW1lb3V0KCgoZnVuY3Rpb24oX3RoaXMpIHtcbiAgICAgICAgICAgIHJldHVybiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgcmV0dXJuIGZpbGUucHJldmlld0VsZW1lbnQuY2xhc3NMaXN0LmFkZChcImR6LWltYWdlLXByZXZpZXdcIik7XG4gICAgICAgICAgICB9O1xuICAgICAgICAgIH0pKHRoaXMpKSwgMSk7XG4gICAgICAgIH1cbiAgICAgIH0sXG4gICAgICBlcnJvcjogZnVuY3Rpb24oZmlsZSwgbWVzc2FnZSkge1xuICAgICAgICB2YXIgbm9kZSwgX2ksIF9sZW4sIF9yZWYsIF9yZXN1bHRzO1xuICAgICAgICBpZiAoZmlsZS5wcmV2aWV3RWxlbWVudCkge1xuICAgICAgICAgIGZpbGUucHJldmlld0VsZW1lbnQuY2xhc3NMaXN0LmFkZChcImR6LWVycm9yXCIpO1xuICAgICAgICAgIGlmICh0eXBlb2YgbWVzc2FnZSAhPT0gXCJTdHJpbmdcIiAmJiBtZXNzYWdlLmVycm9yKSB7XG4gICAgICAgICAgICBtZXNzYWdlID0gbWVzc2FnZS5lcnJvcjtcbiAgICAgICAgICB9XG4gICAgICAgICAgX3JlZiA9IGZpbGUucHJldmlld0VsZW1lbnQucXVlcnlTZWxlY3RvckFsbChcIltkYXRhLWR6LWVycm9ybWVzc2FnZV1cIik7XG4gICAgICAgICAgX3Jlc3VsdHMgPSBbXTtcbiAgICAgICAgICBmb3IgKF9pID0gMCwgX2xlbiA9IF9yZWYubGVuZ3RoOyBfaSA8IF9sZW47IF9pKyspIHtcbiAgICAgICAgICAgIG5vZGUgPSBfcmVmW19pXTtcbiAgICAgICAgICAgIF9yZXN1bHRzLnB1c2gobm9kZS50ZXh0Q29udGVudCA9IG1lc3NhZ2UpO1xuICAgICAgICAgIH1cbiAgICAgICAgICByZXR1cm4gX3Jlc3VsdHM7XG4gICAgICAgIH1cbiAgICAgIH0sXG4gICAgICBlcnJvcm11bHRpcGxlOiBub29wLFxuICAgICAgcHJvY2Vzc2luZzogZnVuY3Rpb24oZmlsZSkge1xuICAgICAgICBpZiAoZmlsZS5wcmV2aWV3RWxlbWVudCkge1xuICAgICAgICAgIGZpbGUucHJldmlld0VsZW1lbnQuY2xhc3NMaXN0LmFkZChcImR6LXByb2Nlc3NpbmdcIik7XG4gICAgICAgICAgaWYgKGZpbGUuX3JlbW92ZUxpbmspIHtcbiAgICAgICAgICAgIHJldHVybiBmaWxlLl9yZW1vdmVMaW5rLnRleHRDb250ZW50ID0gdGhpcy5vcHRpb25zLmRpY3RDYW5jZWxVcGxvYWQ7XG4gICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICB9LFxuICAgICAgcHJvY2Vzc2luZ211bHRpcGxlOiBub29wLFxuICAgICAgdXBsb2FkcHJvZ3Jlc3M6IGZ1bmN0aW9uKGZpbGUsIHByb2dyZXNzLCBieXRlc1NlbnQpIHtcbiAgICAgICAgdmFyIG5vZGUsIF9pLCBfbGVuLCBfcmVmLCBfcmVzdWx0cztcbiAgICAgICAgaWYgKGZpbGUucHJldmlld0VsZW1lbnQpIHtcbiAgICAgICAgICBfcmVmID0gZmlsZS5wcmV2aWV3RWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKFwiW2RhdGEtZHotdXBsb2FkcHJvZ3Jlc3NdXCIpO1xuICAgICAgICAgIF9yZXN1bHRzID0gW107XG4gICAgICAgICAgZm9yIChfaSA9IDAsIF9sZW4gPSBfcmVmLmxlbmd0aDsgX2kgPCBfbGVuOyBfaSsrKSB7XG4gICAgICAgICAgICBub2RlID0gX3JlZltfaV07XG4gICAgICAgICAgICBpZiAobm9kZS5ub2RlTmFtZSA9PT0gJ1BST0dSRVNTJykge1xuICAgICAgICAgICAgICBfcmVzdWx0cy5wdXNoKG5vZGUudmFsdWUgPSBwcm9ncmVzcyk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICBfcmVzdWx0cy5wdXNoKG5vZGUuc3R5bGUud2lkdGggPSBcIlwiICsgcHJvZ3Jlc3MgKyBcIiVcIik7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgfVxuICAgICAgICAgIHJldHVybiBfcmVzdWx0cztcbiAgICAgICAgfVxuICAgICAgfSxcbiAgICAgIHRvdGFsdXBsb2FkcHJvZ3Jlc3M6IG5vb3AsXG4gICAgICBzZW5kaW5nOiBub29wLFxuICAgICAgc2VuZGluZ211bHRpcGxlOiBub29wLFxuICAgICAgc3VjY2VzczogZnVuY3Rpb24oZmlsZSkge1xuICAgICAgICBpZiAoZmlsZS5wcmV2aWV3RWxlbWVudCkge1xuICAgICAgICAgIHJldHVybiBmaWxlLnByZXZpZXdFbGVtZW50LmNsYXNzTGlzdC5hZGQoXCJkei1zdWNjZXNzXCIpO1xuICAgICAgICB9XG4gICAgICB9LFxuICAgICAgc3VjY2Vzc211bHRpcGxlOiBub29wLFxuICAgICAgY2FuY2VsZWQ6IGZ1bmN0aW9uKGZpbGUpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuZW1pdChcImVycm9yXCIsIGZpbGUsIFwiVXBsb2FkIGNhbmNlbGVkLlwiKTtcbiAgICAgIH0sXG4gICAgICBjYW5jZWxlZG11bHRpcGxlOiBub29wLFxuICAgICAgY29tcGxldGU6IGZ1bmN0aW9uKGZpbGUpIHtcbiAgICAgICAgaWYgKGZpbGUuX3JlbW92ZUxpbmspIHtcbiAgICAgICAgICBmaWxlLl9yZW1vdmVMaW5rLnRleHRDb250ZW50ID0gdGhpcy5vcHRpb25zLmRpY3RSZW1vdmVGaWxlO1xuICAgICAgICB9XG4gICAgICAgIGlmIChmaWxlLnByZXZpZXdFbGVtZW50KSB7XG4gICAgICAgICAgcmV0dXJuIGZpbGUucHJldmlld0VsZW1lbnQuY2xhc3NMaXN0LmFkZChcImR6LWNvbXBsZXRlXCIpO1xuICAgICAgICB9XG4gICAgICB9LFxuICAgICAgY29tcGxldGVtdWx0aXBsZTogbm9vcCxcbiAgICAgIG1heGZpbGVzZXhjZWVkZWQ6IG5vb3AsXG4gICAgICBtYXhmaWxlc3JlYWNoZWQ6IG5vb3AsXG4gICAgICBxdWV1ZWNvbXBsZXRlOiBub29wLFxuICAgICAgYWRkZWRmaWxlczogbm9vcCxcbiAgICAgIHByZXZpZXdUZW1wbGF0ZTogXCI8ZGl2IGNsYXNzPVxcXCJkei1wcmV2aWV3IGR6LWZpbGUtcHJldmlld1xcXCI+XFxuICA8ZGl2IGNsYXNzPVxcXCJkei1pbWFnZVxcXCI+PGltZyBkYXRhLWR6LXRodW1ibmFpbCAvPjwvZGl2PlxcbiAgPGRpdiBjbGFzcz1cXFwiZHotZGV0YWlsc1xcXCI+XFxuICAgIDxkaXYgY2xhc3M9XFxcImR6LXNpemVcXFwiPjxzcGFuIGRhdGEtZHotc2l6ZT48L3NwYW4+PC9kaXY+XFxuICAgIDxkaXYgY2xhc3M9XFxcImR6LWZpbGVuYW1lXFxcIj48c3BhbiBkYXRhLWR6LW5hbWU+PC9zcGFuPjwvZGl2PlxcbiAgPC9kaXY+XFxuICA8ZGl2IGNsYXNzPVxcXCJkei1wcm9ncmVzc1xcXCI+PHNwYW4gY2xhc3M9XFxcImR6LXVwbG9hZFxcXCIgZGF0YS1kei11cGxvYWRwcm9ncmVzcz48L3NwYW4+PC9kaXY+XFxuICA8ZGl2IGNsYXNzPVxcXCJkei1lcnJvci1tZXNzYWdlXFxcIj48c3BhbiBkYXRhLWR6LWVycm9ybWVzc2FnZT48L3NwYW4+PC9kaXY+XFxuICA8ZGl2IGNsYXNzPVxcXCJkei1zdWNjZXNzLW1hcmtcXFwiPlxcbiAgICA8c3ZnIHdpZHRoPVxcXCI1NHB4XFxcIiBoZWlnaHQ9XFxcIjU0cHhcXFwiIHZpZXdCb3g9XFxcIjAgMCA1NCA1NFxcXCIgdmVyc2lvbj1cXFwiMS4xXFxcIiB4bWxucz1cXFwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcXFwiIHhtbG5zOnhsaW5rPVxcXCJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rXFxcIiB4bWxuczpza2V0Y2g9XFxcImh0dHA6Ly93d3cuYm9oZW1pYW5jb2RpbmcuY29tL3NrZXRjaC9uc1xcXCI+XFxuICAgICAgPHRpdGxlPkNoZWNrPC90aXRsZT5cXG4gICAgICA8ZGVmcz48L2RlZnM+XFxuICAgICAgPGcgaWQ9XFxcIlBhZ2UtMVxcXCIgc3Ryb2tlPVxcXCJub25lXFxcIiBzdHJva2Utd2lkdGg9XFxcIjFcXFwiIGZpbGw9XFxcIm5vbmVcXFwiIGZpbGwtcnVsZT1cXFwiZXZlbm9kZFxcXCIgc2tldGNoOnR5cGU9XFxcIk1TUGFnZVxcXCI+XFxuICAgICAgICA8cGF0aCBkPVxcXCJNMjMuNSwzMS44NDMxNDU4IEwxNy41ODUyNDE5LDI1LjkyODM4NzcgQzE2LjAyNDgyNTMsMjQuMzY3OTcxMSAxMy40OTEwMjk0LDI0LjM2NjgzNSAxMS45Mjg5MzIyLDI1LjkyODkzMjIgQzEwLjM3MDAxMzYsMjcuNDg3ODUwOCAxMC4zNjY1OTEyLDMwLjAyMzQ0NTUgMTEuOTI4Mzg3NywzMS41ODUyNDE5IEwyMC40MTQ3NTgxLDQwLjA3MTYxMjMgQzIwLjUxMzM5OTksNDAuMTcwMjU0MSAyMC42MTU5MzE1LDQwLjI2MjY2NDkgMjAuNzIxODYxNSw0MC4zNDg4NDM1IEMyMi4yODM1NjY5LDQxLjg3MjU2NTEgMjQuNzk0MjM0LDQxLjg2MjYyMDIgMjYuMzQ2MTU2NCw0MC4zMTA2OTc4IEw0My4zMTA2OTc4LDIzLjM0NjE1NjQgQzQ0Ljg3NzEwMjEsMjEuNzc5NzUyMSA0NC44NzU4MDU3LDE5LjI0ODM4ODcgNDMuMzEzNzA4NSwxNy42ODYyOTE1IEM0MS43NTQ3ODk5LDE2LjEyNzM3MjkgMzkuMjE3NjAzNSwxNi4xMjU1NDIyIDM3LjY1Mzg0MzYsMTcuNjg5MzAyMiBMMjMuNSwzMS44NDMxNDU4IFogTTI3LDUzIEM0MS4zNTk0MDM1LDUzIDUzLDQxLjM1OTQwMzUgNTMsMjcgQzUzLDEyLjY0MDU5NjUgNDEuMzU5NDAzNSwxIDI3LDEgQzEyLjY0MDU5NjUsMSAxLDEyLjY0MDU5NjUgMSwyNyBDMSw0MS4zNTk0MDM1IDEyLjY0MDU5NjUsNTMgMjcsNTMgWlxcXCIgaWQ9XFxcIk92YWwtMlxcXCIgc3Ryb2tlLW9wYWNpdHk9XFxcIjAuMTk4Nzk0MTU4XFxcIiBzdHJva2U9XFxcIiM3NDc0NzRcXFwiIGZpbGwtb3BhY2l0eT1cXFwiMC44MTY1MTk0NzVcXFwiIGZpbGw9XFxcIiNGRkZGRkZcXFwiIHNrZXRjaDp0eXBlPVxcXCJNU1NoYXBlR3JvdXBcXFwiPjwvcGF0aD5cXG4gICAgICA8L2c+XFxuICAgIDwvc3ZnPlxcbiAgPC9kaXY+XFxuICA8ZGl2IGNsYXNzPVxcXCJkei1lcnJvci1tYXJrXFxcIj5cXG4gICAgPHN2ZyB3aWR0aD1cXFwiNTRweFxcXCIgaGVpZ2h0PVxcXCI1NHB4XFxcIiB2aWV3Qm94PVxcXCIwIDAgNTQgNTRcXFwiIHZlcnNpb249XFxcIjEuMVxcXCIgeG1sbnM9XFxcImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXFxcIiB4bWxuczp4bGluaz1cXFwiaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGlua1xcXCIgeG1sbnM6c2tldGNoPVxcXCJodHRwOi8vd3d3LmJvaGVtaWFuY29kaW5nLmNvbS9za2V0Y2gvbnNcXFwiPlxcbiAgICAgIDx0aXRsZT5FcnJvcjwvdGl0bGU+XFxuICAgICAgPGRlZnM+PC9kZWZzPlxcbiAgICAgIDxnIGlkPVxcXCJQYWdlLTFcXFwiIHN0cm9rZT1cXFwibm9uZVxcXCIgc3Ryb2tlLXdpZHRoPVxcXCIxXFxcIiBmaWxsPVxcXCJub25lXFxcIiBmaWxsLXJ1bGU9XFxcImV2ZW5vZGRcXFwiIHNrZXRjaDp0eXBlPVxcXCJNU1BhZ2VcXFwiPlxcbiAgICAgICAgPGcgaWQ9XFxcIkNoZWNrLSstT3ZhbC0yXFxcIiBza2V0Y2g6dHlwZT1cXFwiTVNMYXllckdyb3VwXFxcIiBzdHJva2U9XFxcIiM3NDc0NzRcXFwiIHN0cm9rZS1vcGFjaXR5PVxcXCIwLjE5ODc5NDE1OFxcXCIgZmlsbD1cXFwiI0ZGRkZGRlxcXCIgZmlsbC1vcGFjaXR5PVxcXCIwLjgxNjUxOTQ3NVxcXCI+XFxuICAgICAgICAgIDxwYXRoIGQ9XFxcIk0zMi42NTY4NTQyLDI5IEwzOC4zMTA2OTc4LDIzLjM0NjE1NjQgQzM5Ljg3NzEwMjEsMjEuNzc5NzUyMSAzOS44NzU4MDU3LDE5LjI0ODM4ODcgMzguMzEzNzA4NSwxNy42ODYyOTE1IEMzNi43NTQ3ODk5LDE2LjEyNzM3MjkgMzQuMjE3NjAzNSwxNi4xMjU1NDIyIDMyLjY1Mzg0MzYsMTcuNjg5MzAyMiBMMjcsMjMuMzQzMTQ1OCBMMjEuMzQ2MTU2NCwxNy42ODkzMDIyIEMxOS43ODIzOTY1LDE2LjEyNTU0MjIgMTcuMjQ1MjEwMSwxNi4xMjczNzI5IDE1LjY4NjI5MTUsMTcuNjg2MjkxNSBDMTQuMTI0MTk0MywxOS4yNDgzODg3IDE0LjEyMjg5NzksMjEuNzc5NzUyMSAxNS42ODkzMDIyLDIzLjM0NjE1NjQgTDIxLjM0MzE0NTgsMjkgTDE1LjY4OTMwMjIsMzQuNjUzODQzNiBDMTQuMTIyODk3OSwzNi4yMjAyNDc5IDE0LjEyNDE5NDMsMzguNzUxNjExMyAxNS42ODYyOTE1LDQwLjMxMzcwODUgQzE3LjI0NTIxMDEsNDEuODcyNjI3MSAxOS43ODIzOTY1LDQxLjg3NDQ1NzggMjEuMzQ2MTU2NCw0MC4zMTA2OTc4IEwyNywzNC42NTY4NTQyIEwzMi42NTM4NDM2LDQwLjMxMDY5NzggQzM0LjIxNzYwMzUsNDEuODc0NDU3OCAzNi43NTQ3ODk5LDQxLjg3MjYyNzEgMzguMzEzNzA4NSw0MC4zMTM3MDg1IEMzOS44NzU4MDU3LDM4Ljc1MTYxMTMgMzkuODc3MTAyMSwzNi4yMjAyNDc5IDM4LjMxMDY5NzgsMzQuNjUzODQzNiBMMzIuNjU2ODU0MiwyOSBaIE0yNyw1MyBDNDEuMzU5NDAzNSw1MyA1Myw0MS4zNTk0MDM1IDUzLDI3IEM1MywxMi42NDA1OTY1IDQxLjM1OTQwMzUsMSAyNywxIEMxMi42NDA1OTY1LDEgMSwxMi42NDA1OTY1IDEsMjcgQzEsNDEuMzU5NDAzNSAxMi42NDA1OTY1LDUzIDI3LDUzIFpcXFwiIGlkPVxcXCJPdmFsLTJcXFwiIHNrZXRjaDp0eXBlPVxcXCJNU1NoYXBlR3JvdXBcXFwiPjwvcGF0aD5cXG4gICAgICAgIDwvZz5cXG4gICAgICA8L2c+XFxuICAgIDwvc3ZnPlxcbiAgPC9kaXY+XFxuPC9kaXY+XCJcbiAgICB9O1xuXG4gICAgZXh0ZW5kID0gZnVuY3Rpb24oKSB7XG4gICAgICB2YXIga2V5LCBvYmplY3QsIG9iamVjdHMsIHRhcmdldCwgdmFsLCBfaSwgX2xlbjtcbiAgICAgIHRhcmdldCA9IGFyZ3VtZW50c1swXSwgb2JqZWN0cyA9IDIgPD0gYXJndW1lbnRzLmxlbmd0aCA/IF9fc2xpY2UuY2FsbChhcmd1bWVudHMsIDEpIDogW107XG4gICAgICBmb3IgKF9pID0gMCwgX2xlbiA9IG9iamVjdHMubGVuZ3RoOyBfaSA8IF9sZW47IF9pKyspIHtcbiAgICAgICAgb2JqZWN0ID0gb2JqZWN0c1tfaV07XG4gICAgICAgIGZvciAoa2V5IGluIG9iamVjdCkge1xuICAgICAgICAgIHZhbCA9IG9iamVjdFtrZXldO1xuICAgICAgICAgIHRhcmdldFtrZXldID0gdmFsO1xuICAgICAgICB9XG4gICAgICB9XG4gICAgICByZXR1cm4gdGFyZ2V0O1xuICAgIH07XG5cbiAgICBmdW5jdGlvbiBEcm9wem9uZShlbGVtZW50LCBvcHRpb25zKSB7XG4gICAgICB2YXIgZWxlbWVudE9wdGlvbnMsIGZhbGxiYWNrLCBfcmVmO1xuICAgICAgdGhpcy5lbGVtZW50ID0gZWxlbWVudDtcbiAgICAgIHRoaXMudmVyc2lvbiA9IERyb3B6b25lLnZlcnNpb247XG4gICAgICB0aGlzLmRlZmF1bHRPcHRpb25zLnByZXZpZXdUZW1wbGF0ZSA9IHRoaXMuZGVmYXVsdE9wdGlvbnMucHJldmlld1RlbXBsYXRlLnJlcGxhY2UoL1xcbiovZywgXCJcIik7XG4gICAgICB0aGlzLmNsaWNrYWJsZUVsZW1lbnRzID0gW107XG4gICAgICB0aGlzLmxpc3RlbmVycyA9IFtdO1xuICAgICAgdGhpcy5maWxlcyA9IFtdO1xuICAgICAgaWYgKHR5cGVvZiB0aGlzLmVsZW1lbnQgPT09IFwic3RyaW5nXCIpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50ID0gZG9jdW1lbnQucXVlcnlTZWxlY3Rvcih0aGlzLmVsZW1lbnQpO1xuICAgICAgfVxuICAgICAgaWYgKCEodGhpcy5lbGVtZW50ICYmICh0aGlzLmVsZW1lbnQubm9kZVR5cGUgIT0gbnVsbCkpKSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcihcIkludmFsaWQgZHJvcHpvbmUgZWxlbWVudC5cIik7XG4gICAgICB9XG4gICAgICBpZiAodGhpcy5lbGVtZW50LmRyb3B6b25lKSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcihcIkRyb3B6b25lIGFscmVhZHkgYXR0YWNoZWQuXCIpO1xuICAgICAgfVxuICAgICAgRHJvcHpvbmUuaW5zdGFuY2VzLnB1c2godGhpcyk7XG4gICAgICB0aGlzLmVsZW1lbnQuZHJvcHpvbmUgPSB0aGlzO1xuICAgICAgZWxlbWVudE9wdGlvbnMgPSAoX3JlZiA9IERyb3B6b25lLm9wdGlvbnNGb3JFbGVtZW50KHRoaXMuZWxlbWVudCkpICE9IG51bGwgPyBfcmVmIDoge307XG4gICAgICB0aGlzLm9wdGlvbnMgPSBleHRlbmQoe30sIHRoaXMuZGVmYXVsdE9wdGlvbnMsIGVsZW1lbnRPcHRpb25zLCBvcHRpb25zICE9IG51bGwgPyBvcHRpb25zIDoge30pO1xuICAgICAgaWYgKHRoaXMub3B0aW9ucy5mb3JjZUZhbGxiYWNrIHx8ICFEcm9wem9uZS5pc0Jyb3dzZXJTdXBwb3J0ZWQoKSkge1xuICAgICAgICByZXR1cm4gdGhpcy5vcHRpb25zLmZhbGxiYWNrLmNhbGwodGhpcyk7XG4gICAgICB9XG4gICAgICBpZiAodGhpcy5vcHRpb25zLnVybCA9PSBudWxsKSB7XG4gICAgICAgIHRoaXMub3B0aW9ucy51cmwgPSB0aGlzLmVsZW1lbnQuZ2V0QXR0cmlidXRlKFwiYWN0aW9uXCIpO1xuICAgICAgfVxuICAgICAgaWYgKCF0aGlzLm9wdGlvbnMudXJsKSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcihcIk5vIFVSTCBwcm92aWRlZC5cIik7XG4gICAgICB9XG4gICAgICBpZiAodGhpcy5vcHRpb25zLmFjY2VwdGVkRmlsZXMgJiYgdGhpcy5vcHRpb25zLmFjY2VwdGVkTWltZVR5cGVzKSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcihcIllvdSBjYW4ndCBwcm92aWRlIGJvdGggJ2FjY2VwdGVkRmlsZXMnIGFuZCAnYWNjZXB0ZWRNaW1lVHlwZXMnLiAnYWNjZXB0ZWRNaW1lVHlwZXMnIGlzIGRlcHJlY2F0ZWQuXCIpO1xuICAgICAgfVxuICAgICAgaWYgKHRoaXMub3B0aW9ucy5hY2NlcHRlZE1pbWVUeXBlcykge1xuICAgICAgICB0aGlzLm9wdGlvbnMuYWNjZXB0ZWRGaWxlcyA9IHRoaXMub3B0aW9ucy5hY2NlcHRlZE1pbWVUeXBlcztcbiAgICAgICAgZGVsZXRlIHRoaXMub3B0aW9ucy5hY2NlcHRlZE1pbWVUeXBlcztcbiAgICAgIH1cbiAgICAgIHRoaXMub3B0aW9ucy5tZXRob2QgPSB0aGlzLm9wdGlvbnMubWV0aG9kLnRvVXBwZXJDYXNlKCk7XG4gICAgICBpZiAoKGZhbGxiYWNrID0gdGhpcy5nZXRFeGlzdGluZ0ZhbGxiYWNrKCkpICYmIGZhbGxiYWNrLnBhcmVudE5vZGUpIHtcbiAgICAgICAgZmFsbGJhY2sucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChmYWxsYmFjayk7XG4gICAgICB9XG4gICAgICBpZiAodGhpcy5vcHRpb25zLnByZXZpZXdzQ29udGFpbmVyICE9PSBmYWxzZSkge1xuICAgICAgICBpZiAodGhpcy5vcHRpb25zLnByZXZpZXdzQ29udGFpbmVyKSB7XG4gICAgICAgICAgdGhpcy5wcmV2aWV3c0NvbnRhaW5lciA9IERyb3B6b25lLmdldEVsZW1lbnQodGhpcy5vcHRpb25zLnByZXZpZXdzQ29udGFpbmVyLCBcInByZXZpZXdzQ29udGFpbmVyXCIpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIHRoaXMucHJldmlld3NDb250YWluZXIgPSB0aGlzLmVsZW1lbnQ7XG4gICAgICAgIH1cbiAgICAgIH1cbiAgICAgIGlmICh0aGlzLm9wdGlvbnMuY2xpY2thYmxlKSB7XG4gICAgICAgIGlmICh0aGlzLm9wdGlvbnMuY2xpY2thYmxlID09PSB0cnVlKSB7XG4gICAgICAgICAgdGhpcy5jbGlja2FibGVFbGVtZW50cyA9IFt0aGlzLmVsZW1lbnRdO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIHRoaXMuY2xpY2thYmxlRWxlbWVudHMgPSBEcm9wem9uZS5nZXRFbGVtZW50cyh0aGlzLm9wdGlvbnMuY2xpY2thYmxlLCBcImNsaWNrYWJsZVwiKTtcbiAgICAgICAgfVxuICAgICAgfVxuICAgICAgdGhpcy5pbml0KCk7XG4gICAgfVxuXG4gICAgRHJvcHpvbmUucHJvdG90eXBlLmdldEFjY2VwdGVkRmlsZXMgPSBmdW5jdGlvbigpIHtcbiAgICAgIHZhciBmaWxlLCBfaSwgX2xlbiwgX3JlZiwgX3Jlc3VsdHM7XG4gICAgICBfcmVmID0gdGhpcy5maWxlcztcbiAgICAgIF9yZXN1bHRzID0gW107XG4gICAgICBmb3IgKF9pID0gMCwgX2xlbiA9IF9yZWYubGVuZ3RoOyBfaSA8IF9sZW47IF9pKyspIHtcbiAgICAgICAgZmlsZSA9IF9yZWZbX2ldO1xuICAgICAgICBpZiAoZmlsZS5hY2NlcHRlZCkge1xuICAgICAgICAgIF9yZXN1bHRzLnB1c2goZmlsZSk7XG4gICAgICAgIH1cbiAgICAgIH1cbiAgICAgIHJldHVybiBfcmVzdWx0cztcbiAgICB9O1xuXG4gICAgRHJvcHpvbmUucHJvdG90eXBlLmdldFJlamVjdGVkRmlsZXMgPSBmdW5jdGlvbigpIHtcbiAgICAgIHZhciBmaWxlLCBfaSwgX2xlbiwgX3JlZiwgX3Jlc3VsdHM7XG4gICAgICBfcmVmID0gdGhpcy5maWxlcztcbiAgICAgIF9yZXN1bHRzID0gW107XG4gICAgICBmb3IgKF9pID0gMCwgX2xlbiA9IF9yZWYubGVuZ3RoOyBfaSA8IF9sZW47IF9pKyspIHtcbiAgICAgICAgZmlsZSA9IF9yZWZbX2ldO1xuICAgICAgICBpZiAoIWZpbGUuYWNjZXB0ZWQpIHtcbiAgICAgICAgICBfcmVzdWx0cy5wdXNoKGZpbGUpO1xuICAgICAgICB9XG4gICAgICB9XG4gICAgICByZXR1cm4gX3Jlc3VsdHM7XG4gICAgfTtcblxuICAgIERyb3B6b25lLnByb3RvdHlwZS5nZXRGaWxlc1dpdGhTdGF0dXMgPSBmdW5jdGlvbihzdGF0dXMpIHtcbiAgICAgIHZhciBmaWxlLCBfaSwgX2xlbiwgX3JlZiwgX3Jlc3VsdHM7XG4gICAgICBfcmVmID0gdGhpcy5maWxlcztcbiAgICAgIF9yZXN1bHRzID0gW107XG4gICAgICBmb3IgKF9pID0gMCwgX2xlbiA9IF9yZWYubGVuZ3RoOyBfaSA8IF9sZW47IF9pKyspIHtcbiAgICAgICAgZmlsZSA9IF9yZWZbX2ldO1xuICAgICAgICBpZiAoZmlsZS5zdGF0dXMgPT09IHN0YXR1cykge1xuICAgICAgICAgIF9yZXN1bHRzLnB1c2goZmlsZSk7XG4gICAgICAgIH1cbiAgICAgIH1cbiAgICAgIHJldHVybiBfcmVzdWx0cztcbiAgICB9O1xuXG4gICAgRHJvcHpvbmUucHJvdG90eXBlLmdldFF1ZXVlZEZpbGVzID0gZnVuY3Rpb24oKSB7XG4gICAgICByZXR1cm4gdGhpcy5nZXRGaWxlc1dpdGhTdGF0dXMoRHJvcHpvbmUuUVVFVUVEKTtcbiAgICB9O1xuXG4gICAgRHJvcHpvbmUucHJvdG90eXBlLmdldFVwbG9hZGluZ0ZpbGVzID0gZnVuY3Rpb24oKSB7XG4gICAgICByZXR1cm4gdGhpcy5nZXRGaWxlc1dpdGhTdGF0dXMoRHJvcHpvbmUuVVBMT0FESU5HKTtcbiAgICB9O1xuXG4gICAgRHJvcHpvbmUucHJvdG90eXBlLmdldEFkZGVkRmlsZXMgPSBmdW5jdGlvbigpIHtcbiAgICAgIHJldHVybiB0aGlzLmdldEZpbGVzV2l0aFN0YXR1cyhEcm9wem9uZS5BRERFRCk7XG4gICAgfTtcblxuICAgIERyb3B6b25lLnByb3RvdHlwZS5nZXRBY3RpdmVGaWxlcyA9IGZ1bmN0aW9uKCkge1xuICAgICAgdmFyIGZpbGUsIF9pLCBfbGVuLCBfcmVmLCBfcmVzdWx0cztcbiAgICAgIF9yZWYgPSB0aGlzLmZpbGVzO1xuICAgICAgX3Jlc3VsdHMgPSBbXTtcbiAgICAgIGZvciAoX2kgPSAwLCBfbGVuID0gX3JlZi5sZW5ndGg7IF9pIDwgX2xlbjsgX2krKykge1xuICAgICAgICBmaWxlID0gX3JlZltfaV07XG4gICAgICAgIGlmIChmaWxlLnN0YXR1cyA9PT0gRHJvcHpvbmUuVVBMT0FESU5HIHx8IGZpbGUuc3RhdHVzID09PSBEcm9wem9uZS5RVUVVRUQpIHtcbiAgICAgICAgICBfcmVzdWx0cy5wdXNoKGZpbGUpO1xuICAgICAgICB9XG4gICAgICB9XG4gICAgICByZXR1cm4gX3Jlc3VsdHM7XG4gICAgfTtcblxuICAgIERyb3B6b25lLnByb3RvdHlwZS5pbml0ID0gZnVuY3Rpb24oKSB7XG4gICAgICB2YXIgZXZlbnROYW1lLCBub1Byb3BhZ2F0aW9uLCBzZXR1cEhpZGRlbkZpbGVJbnB1dCwgX2ksIF9sZW4sIF9yZWYsIF9yZWYxO1xuICAgICAgaWYgKHRoaXMuZWxlbWVudC50YWdOYW1lID09PSBcImZvcm1cIikge1xuICAgICAgICB0aGlzLmVsZW1lbnQuc2V0QXR0cmlidXRlKFwiZW5jdHlwZVwiLCBcIm11bHRpcGFydC9mb3JtLWRhdGFcIik7XG4gICAgICB9XG4gICAgICBpZiAodGhpcy5lbGVtZW50LmNsYXNzTGlzdC5jb250YWlucyhcImRyb3B6b25lXCIpICYmICF0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvcihcIi5kei1tZXNzYWdlXCIpKSB7XG4gICAgICAgIHRoaXMuZWxlbWVudC5hcHBlbmRDaGlsZChEcm9wem9uZS5jcmVhdGVFbGVtZW50KFwiPGRpdiBjbGFzcz1cXFwiZHotZGVmYXVsdCBkei1tZXNzYWdlXFxcIj48c3Bhbj5cIiArIHRoaXMub3B0aW9ucy5kaWN0RGVmYXVsdE1lc3NhZ2UgKyBcIjwvc3Bhbj48L2Rpdj5cIikpO1xuICAgICAgfVxuICAgICAgaWYgKHRoaXMuY2xpY2thYmxlRWxlbWVudHMubGVuZ3RoKSB7XG4gICAgICAgIHNldHVwSGlkZGVuRmlsZUlucHV0ID0gKGZ1bmN0aW9uKF90aGlzKSB7XG4gICAgICAgICAgcmV0dXJuIGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgaWYgKF90aGlzLmhpZGRlbkZpbGVJbnB1dCkge1xuICAgICAgICAgICAgICBfdGhpcy5oaWRkZW5GaWxlSW5wdXQucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChfdGhpcy5oaWRkZW5GaWxlSW5wdXQpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgX3RoaXMuaGlkZGVuRmlsZUlucHV0ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImlucHV0XCIpO1xuICAgICAgICAgICAgX3RoaXMuaGlkZGVuRmlsZUlucHV0LnNldEF0dHJpYnV0ZShcInR5cGVcIiwgXCJmaWxlXCIpO1xuICAgICAgICAgICAgaWYgKChfdGhpcy5vcHRpb25zLm1heEZpbGVzID09IG51bGwpIHx8IF90aGlzLm9wdGlvbnMubWF4RmlsZXMgPiAxKSB7XG4gICAgICAgICAgICAgIF90aGlzLmhpZGRlbkZpbGVJbnB1dC5zZXRBdHRyaWJ1dGUoXCJtdWx0aXBsZVwiLCBcIm11bHRpcGxlXCIpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgX3RoaXMuaGlkZGVuRmlsZUlucHV0LmNsYXNzTmFtZSA9IFwiZHotaGlkZGVuLWlucHV0XCI7XG4gICAgICAgICAgICBpZiAoX3RoaXMub3B0aW9ucy5hY2NlcHRlZEZpbGVzICE9IG51bGwpIHtcbiAgICAgICAgICAgICAgX3RoaXMuaGlkZGVuRmlsZUlucHV0LnNldEF0dHJpYnV0ZShcImFjY2VwdFwiLCBfdGhpcy5vcHRpb25zLmFjY2VwdGVkRmlsZXMpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYgKF90aGlzLm9wdGlvbnMuY2FwdHVyZSAhPSBudWxsKSB7XG4gICAgICAgICAgICAgIF90aGlzLmhpZGRlbkZpbGVJbnB1dC5zZXRBdHRyaWJ1dGUoXCJjYXB0dXJlXCIsIF90aGlzLm9wdGlvbnMuY2FwdHVyZSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBfdGhpcy5oaWRkZW5GaWxlSW5wdXQuc3R5bGUudmlzaWJpbGl0eSA9IFwiaGlkZGVuXCI7XG4gICAgICAgICAgICBfdGhpcy5oaWRkZW5GaWxlSW5wdXQuc3R5bGUucG9zaXRpb24gPSBcImFic29sdXRlXCI7XG4gICAgICAgICAgICBfdGhpcy5oaWRkZW5GaWxlSW5wdXQuc3R5bGUudG9wID0gXCIwXCI7XG4gICAgICAgICAgICBfdGhpcy5oaWRkZW5GaWxlSW5wdXQuc3R5bGUubGVmdCA9IFwiMFwiO1xuICAgICAgICAgICAgX3RoaXMuaGlkZGVuRmlsZUlucHV0LnN0eWxlLmhlaWdodCA9IFwiMFwiO1xuICAgICAgICAgICAgX3RoaXMuaGlkZGVuRmlsZUlucHV0LnN0eWxlLndpZHRoID0gXCIwXCI7XG4gICAgICAgICAgICBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKF90aGlzLm9wdGlvbnMuaGlkZGVuSW5wdXRDb250YWluZXIpLmFwcGVuZENoaWxkKF90aGlzLmhpZGRlbkZpbGVJbnB1dCk7XG4gICAgICAgICAgICByZXR1cm4gX3RoaXMuaGlkZGVuRmlsZUlucHV0LmFkZEV2ZW50TGlzdGVuZXIoXCJjaGFuZ2VcIiwgZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgIHZhciBmaWxlLCBmaWxlcywgX2ksIF9sZW47XG4gICAgICAgICAgICAgIGZpbGVzID0gX3RoaXMuaGlkZGVuRmlsZUlucHV0LmZpbGVzO1xuICAgICAgICAgICAgICBpZiAoZmlsZXMubGVuZ3RoKSB7XG4gICAgICAgICAgICAgICAgZm9yIChfaSA9IDAsIF9sZW4gPSBmaWxlcy5sZW5ndGg7IF9pIDwgX2xlbjsgX2krKykge1xuICAgICAgICAgICAgICAgICAgZmlsZSA9IGZpbGVzW19pXTtcbiAgICAgICAgICAgICAgICAgIF90aGlzLmFkZEZpbGUoZmlsZSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgIF90aGlzLmVtaXQoXCJhZGRlZGZpbGVzXCIsIGZpbGVzKTtcbiAgICAgICAgICAgICAgcmV0dXJuIHNldHVwSGlkZGVuRmlsZUlucHV0KCk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICB9O1xuICAgICAgICB9KSh0aGlzKTtcbiAgICAgICAgc2V0dXBIaWRkZW5GaWxlSW5wdXQoKTtcbiAgICAgIH1cbiAgICAgIHRoaXMuVVJMID0gKF9yZWYgPSB3aW5kb3cuVVJMKSAhPSBudWxsID8gX3JlZiA6IHdpbmRvdy53ZWJraXRVUkw7XG4gICAgICBfcmVmMSA9IHRoaXMuZXZlbnRzO1xuICAgICAgZm9yIChfaSA9IDAsIF9sZW4gPSBfcmVmMS5sZW5ndGg7IF9pIDwgX2xlbjsgX2krKykge1xuICAgICAgICBldmVudE5hbWUgPSBfcmVmMVtfaV07XG4gICAgICAgIHRoaXMub24oZXZlbnROYW1lLCB0aGlzLm9wdGlvbnNbZXZlbnROYW1lXSk7XG4gICAgICB9XG4gICAgICB0aGlzLm9uKFwidXBsb2FkcHJvZ3Jlc3NcIiwgKGZ1bmN0aW9uKF90aGlzKSB7XG4gICAgICAgIHJldHVybiBmdW5jdGlvbigpIHtcbiAgICAgICAgICByZXR1cm4gX3RoaXMudXBkYXRlVG90YWxVcGxvYWRQcm9ncmVzcygpO1xuICAgICAgICB9O1xuICAgICAgfSkodGhpcykpO1xuICAgICAgdGhpcy5vbihcInJlbW92ZWRmaWxlXCIsIChmdW5jdGlvbihfdGhpcykge1xuICAgICAgICByZXR1cm4gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgcmV0dXJuIF90aGlzLnVwZGF0ZVRvdGFsVXBsb2FkUHJvZ3Jlc3MoKTtcbiAgICAgICAgfTtcbiAgICAgIH0pKHRoaXMpKTtcbiAgICAgIHRoaXMub24oXCJjYW5jZWxlZFwiLCAoZnVuY3Rpb24oX3RoaXMpIHtcbiAgICAgICAgcmV0dXJuIGZ1bmN0aW9uKGZpbGUpIHtcbiAgICAgICAgICByZXR1cm4gX3RoaXMuZW1pdChcImNvbXBsZXRlXCIsIGZpbGUpO1xuICAgICAgICB9O1xuICAgICAgfSkodGhpcykpO1xuICAgICAgdGhpcy5vbihcImNvbXBsZXRlXCIsIChmdW5jdGlvbihfdGhpcykge1xuICAgICAgICByZXR1cm4gZnVuY3Rpb24oZmlsZSkge1xuICAgICAgICAgIGlmIChfdGhpcy5nZXRBZGRlZEZpbGVzKCkubGVuZ3RoID09PSAwICYmIF90aGlzLmdldFVwbG9hZGluZ0ZpbGVzKCkubGVuZ3RoID09PSAwICYmIF90aGlzLmdldFF1ZXVlZEZpbGVzKCkubGVuZ3RoID09PSAwKSB7XG4gICAgICAgICAgICByZXR1cm4gc2V0VGltZW91dCgoZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgIHJldHVybiBfdGhpcy5lbWl0KFwicXVldWVjb21wbGV0ZVwiKTtcbiAgICAgICAgICAgIH0pLCAwKTtcbiAgICAgICAgICB9XG4gICAgICAgIH07XG4gICAgICB9KSh0aGlzKSk7XG4gICAgICBub1Byb3BhZ2F0aW9uID0gZnVuY3Rpb24oZSkge1xuICAgICAgICBlLnN0b3BQcm9wYWdhdGlvbigpO1xuICAgICAgICBpZiAoZS5wcmV2ZW50RGVmYXVsdCkge1xuICAgICAgICAgIHJldHVybiBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgcmV0dXJuIGUucmV0dXJuVmFsdWUgPSBmYWxzZTtcbiAgICAgICAgfVxuICAgICAgfTtcbiAgICAgIHRoaXMubGlzdGVuZXJzID0gW1xuICAgICAgICB7XG4gICAgICAgICAgZWxlbWVudDogdGhpcy5lbGVtZW50LFxuICAgICAgICAgIGV2ZW50czoge1xuICAgICAgICAgICAgXCJkcmFnc3RhcnRcIjogKGZ1bmN0aW9uKF90aGlzKSB7XG4gICAgICAgICAgICAgIHJldHVybiBmdW5jdGlvbihlKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIF90aGlzLmVtaXQoXCJkcmFnc3RhcnRcIiwgZSk7XG4gICAgICAgICAgICAgIH07XG4gICAgICAgICAgICB9KSh0aGlzKSxcbiAgICAgICAgICAgIFwiZHJhZ2VudGVyXCI6IChmdW5jdGlvbihfdGhpcykge1xuICAgICAgICAgICAgICByZXR1cm4gZnVuY3Rpb24oZSkge1xuICAgICAgICAgICAgICAgIG5vUHJvcGFnYXRpb24oZSk7XG4gICAgICAgICAgICAgICAgcmV0dXJuIF90aGlzLmVtaXQoXCJkcmFnZW50ZXJcIiwgZSk7XG4gICAgICAgICAgICAgIH07XG4gICAgICAgICAgICB9KSh0aGlzKSxcbiAgICAgICAgICAgIFwiZHJhZ292ZXJcIjogKGZ1bmN0aW9uKF90aGlzKSB7XG4gICAgICAgICAgICAgIHJldHVybiBmdW5jdGlvbihlKSB7XG4gICAgICAgICAgICAgICAgdmFyIGVmY3Q7XG4gICAgICAgICAgICAgICAgdHJ5IHtcbiAgICAgICAgICAgICAgICAgIGVmY3QgPSBlLmRhdGFUcmFuc2Zlci5lZmZlY3RBbGxvd2VkO1xuICAgICAgICAgICAgICAgIH0gY2F0Y2ggKF9lcnJvcikge31cbiAgICAgICAgICAgICAgICBlLmRhdGFUcmFuc2Zlci5kcm9wRWZmZWN0ID0gJ21vdmUnID09PSBlZmN0IHx8ICdsaW5rTW92ZScgPT09IGVmY3QgPyAnbW92ZScgOiAnY29weSc7XG4gICAgICAgICAgICAgICAgbm9Qcm9wYWdhdGlvbihlKTtcbiAgICAgICAgICAgICAgICByZXR1cm4gX3RoaXMuZW1pdChcImRyYWdvdmVyXCIsIGUpO1xuICAgICAgICAgICAgICB9O1xuICAgICAgICAgICAgfSkodGhpcyksXG4gICAgICAgICAgICBcImRyYWdsZWF2ZVwiOiAoZnVuY3Rpb24oX3RoaXMpIHtcbiAgICAgICAgICAgICAgcmV0dXJuIGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gX3RoaXMuZW1pdChcImRyYWdsZWF2ZVwiLCBlKTtcbiAgICAgICAgICAgICAgfTtcbiAgICAgICAgICAgIH0pKHRoaXMpLFxuICAgICAgICAgICAgXCJkcm9wXCI6IChmdW5jdGlvbihfdGhpcykge1xuICAgICAgICAgICAgICByZXR1cm4gZnVuY3Rpb24oZSkge1xuICAgICAgICAgICAgICAgIG5vUHJvcGFnYXRpb24oZSk7XG4gICAgICAgICAgICAgICAgcmV0dXJuIF90aGlzLmRyb3AoZSk7XG4gICAgICAgICAgICAgIH07XG4gICAgICAgICAgICB9KSh0aGlzKSxcbiAgICAgICAgICAgIFwiZHJhZ2VuZFwiOiAoZnVuY3Rpb24oX3RoaXMpIHtcbiAgICAgICAgICAgICAgcmV0dXJuIGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gX3RoaXMuZW1pdChcImRyYWdlbmRcIiwgZSk7XG4gICAgICAgICAgICAgIH07XG4gICAgICAgICAgICB9KSh0aGlzKVxuICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgXTtcbiAgICAgIHRoaXMuY2xpY2thYmxlRWxlbWVudHMuZm9yRWFjaCgoZnVuY3Rpb24oX3RoaXMpIHtcbiAgICAgICAgcmV0dXJuIGZ1bmN0aW9uKGNsaWNrYWJsZUVsZW1lbnQpIHtcbiAgICAgICAgICByZXR1cm4gX3RoaXMubGlzdGVuZXJzLnB1c2goe1xuICAgICAgICAgICAgZWxlbWVudDogY2xpY2thYmxlRWxlbWVudCxcbiAgICAgICAgICAgIGV2ZW50czoge1xuICAgICAgICAgICAgICBcImNsaWNrXCI6IGZ1bmN0aW9uKGV2dCkge1xuICAgICAgICAgICAgICAgIGlmICgoY2xpY2thYmxlRWxlbWVudCAhPT0gX3RoaXMuZWxlbWVudCkgfHwgKGV2dC50YXJnZXQgPT09IF90aGlzLmVsZW1lbnQgfHwgRHJvcHpvbmUuZWxlbWVudEluc2lkZShldnQudGFyZ2V0LCBfdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoXCIuZHotbWVzc2FnZVwiKSkpKSB7XG4gICAgICAgICAgICAgICAgICBfdGhpcy5oaWRkZW5GaWxlSW5wdXQuY2xpY2soKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgcmV0dXJuIHRydWU7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9KTtcbiAgICAgICAgfTtcbiAgICAgIH0pKHRoaXMpKTtcbiAgICAgIHRoaXMuZW5hYmxlKCk7XG4gICAgICByZXR1cm4gdGhpcy5vcHRpb25zLmluaXQuY2FsbCh0aGlzKTtcbiAgICB9O1xuXG4gICAgRHJvcHpvbmUucHJvdG90eXBlLmRlc3Ryb3kgPSBmdW5jdGlvbigpIHtcbiAgICAgIHZhciBfcmVmO1xuICAgICAgdGhpcy5kaXNhYmxlKCk7XG4gICAgICB0aGlzLnJlbW92ZUFsbEZpbGVzKHRydWUpO1xuICAgICAgaWYgKChfcmVmID0gdGhpcy5oaWRkZW5GaWxlSW5wdXQpICE9IG51bGwgPyBfcmVmLnBhcmVudE5vZGUgOiB2b2lkIDApIHtcbiAgICAgICAgdGhpcy5oaWRkZW5GaWxlSW5wdXQucGFyZW50Tm9kZS5yZW1vdmVDaGlsZCh0aGlzLmhpZGRlbkZpbGVJbnB1dCk7XG4gICAgICAgIHRoaXMuaGlkZGVuRmlsZUlucHV0ID0gbnVsbDtcbiAgICAgIH1cbiAgICAgIGRlbGV0ZSB0aGlzLmVsZW1lbnQuZHJvcHpvbmU7XG4gICAgICByZXR1cm4gRHJvcHpvbmUuaW5zdGFuY2VzLnNwbGljZShEcm9wem9uZS5pbnN0YW5jZXMuaW5kZXhPZih0aGlzKSwgMSk7XG4gICAgfTtcblxuICAgIERyb3B6b25lLnByb3RvdHlwZS51cGRhdGVUb3RhbFVwbG9hZFByb2dyZXNzID0gZnVuY3Rpb24oKSB7XG4gICAgICB2YXIgYWN0aXZlRmlsZXMsIGZpbGUsIHRvdGFsQnl0ZXMsIHRvdGFsQnl0ZXNTZW50LCB0b3RhbFVwbG9hZFByb2dyZXNzLCBfaSwgX2xlbiwgX3JlZjtcbiAgICAgIHRvdGFsQnl0ZXNTZW50ID0gMDtcbiAgICAgIHRvdGFsQnl0ZXMgPSAwO1xuICAgICAgYWN0aXZlRmlsZXMgPSB0aGlzLmdldEFjdGl2ZUZpbGVzKCk7XG4gICAgICBpZiAoYWN0aXZlRmlsZXMubGVuZ3RoKSB7XG4gICAgICAgIF9yZWYgPSB0aGlzLmdldEFjdGl2ZUZpbGVzKCk7XG4gICAgICAgIGZvciAoX2kgPSAwLCBfbGVuID0gX3JlZi5sZW5ndGg7IF9pIDwgX2xlbjsgX2krKykge1xuICAgICAgICAgIGZpbGUgPSBfcmVmW19pXTtcbiAgICAgICAgICB0b3RhbEJ5dGVzU2VudCArPSBmaWxlLnVwbG9hZC5ieXRlc1NlbnQ7XG4gICAgICAgICAgdG90YWxCeXRlcyArPSBmaWxlLnVwbG9hZC50b3RhbDtcbiAgICAgICAgfVxuICAgICAgICB0b3RhbFVwbG9hZFByb2dyZXNzID0gMTAwICogdG90YWxCeXRlc1NlbnQgLyB0b3RhbEJ5dGVzO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgdG90YWxVcGxvYWRQcm9ncmVzcyA9IDEwMDtcbiAgICAgIH1cbiAgICAgIHJldHVybiB0aGlzLmVtaXQoXCJ0b3RhbHVwbG9hZHByb2dyZXNzXCIsIHRvdGFsVXBsb2FkUHJvZ3Jlc3MsIHRvdGFsQnl0ZXMsIHRvdGFsQnl0ZXNTZW50KTtcbiAgICB9O1xuXG4gICAgRHJvcHpvbmUucHJvdG90eXBlLl9nZXRQYXJhbU5hbWUgPSBmdW5jdGlvbihuKSB7XG4gICAgICBpZiAodHlwZW9mIHRoaXMub3B0aW9ucy5wYXJhbU5hbWUgPT09IFwiZnVuY3Rpb25cIikge1xuICAgICAgICByZXR1cm4gdGhpcy5vcHRpb25zLnBhcmFtTmFtZShuKTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIHJldHVybiBcIlwiICsgdGhpcy5vcHRpb25zLnBhcmFtTmFtZSArICh0aGlzLm9wdGlvbnMudXBsb2FkTXVsdGlwbGUgPyBcIltcIiArIG4gKyBcIl1cIiA6IFwiXCIpO1xuICAgICAgfVxuICAgIH07XG5cbiAgICBEcm9wem9uZS5wcm90b3R5cGUuX3JlbmFtZUZpbGVuYW1lID0gZnVuY3Rpb24obmFtZSkge1xuICAgICAgaWYgKHR5cGVvZiB0aGlzLm9wdGlvbnMucmVuYW1lRmlsZW5hbWUgIT09IFwiZnVuY3Rpb25cIikge1xuICAgICAgICByZXR1cm4gbmFtZTtcbiAgICAgIH1cbiAgICAgIHJldHVybiB0aGlzLm9wdGlvbnMucmVuYW1lRmlsZW5hbWUobmFtZSk7XG4gICAgfTtcblxuICAgIERyb3B6b25lLnByb3RvdHlwZS5nZXRGYWxsYmFja0Zvcm0gPSBmdW5jdGlvbigpIHtcbiAgICAgIHZhciBleGlzdGluZ0ZhbGxiYWNrLCBmaWVsZHMsIGZpZWxkc1N0cmluZywgZm9ybTtcbiAgICAgIGlmIChleGlzdGluZ0ZhbGxiYWNrID0gdGhpcy5nZXRFeGlzdGluZ0ZhbGxiYWNrKCkpIHtcbiAgICAgICAgcmV0dXJuIGV4aXN0aW5nRmFsbGJhY2s7XG4gICAgICB9XG4gICAgICBmaWVsZHNTdHJpbmcgPSBcIjxkaXYgY2xhc3M9XFxcImR6LWZhbGxiYWNrXFxcIj5cIjtcbiAgICAgIGlmICh0aGlzLm9wdGlvbnMuZGljdEZhbGxiYWNrVGV4dCkge1xuICAgICAgICBmaWVsZHNTdHJpbmcgKz0gXCI8cD5cIiArIHRoaXMub3B0aW9ucy5kaWN0RmFsbGJhY2tUZXh0ICsgXCI8L3A+XCI7XG4gICAgICB9XG4gICAgICBmaWVsZHNTdHJpbmcgKz0gXCI8aW5wdXQgdHlwZT1cXFwiZmlsZVxcXCIgbmFtZT1cXFwiXCIgKyAodGhpcy5fZ2V0UGFyYW1OYW1lKDApKSArIFwiXFxcIiBcIiArICh0aGlzLm9wdGlvbnMudXBsb2FkTXVsdGlwbGUgPyAnbXVsdGlwbGU9XCJtdWx0aXBsZVwiJyA6IHZvaWQgMCkgKyBcIiAvPjxpbnB1dCB0eXBlPVxcXCJzdWJtaXRcXFwiIHZhbHVlPVxcXCJVcGxvYWQhXFxcIj48L2Rpdj5cIjtcbiAgICAgIGZpZWxkcyA9IERyb3B6b25lLmNyZWF0ZUVsZW1lbnQoZmllbGRzU3RyaW5nKTtcbiAgICAgIGlmICh0aGlzLmVsZW1lbnQudGFnTmFtZSAhPT0gXCJGT1JNXCIpIHtcbiAgICAgICAgZm9ybSA9IERyb3B6b25lLmNyZWF0ZUVsZW1lbnQoXCI8Zm9ybSBhY3Rpb249XFxcIlwiICsgdGhpcy5vcHRpb25zLnVybCArIFwiXFxcIiBlbmN0eXBlPVxcXCJtdWx0aXBhcnQvZm9ybS1kYXRhXFxcIiBtZXRob2Q9XFxcIlwiICsgdGhpcy5vcHRpb25zLm1ldGhvZCArIFwiXFxcIj48L2Zvcm0+XCIpO1xuICAgICAgICBmb3JtLmFwcGVuZENoaWxkKGZpZWxkcyk7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICB0aGlzLmVsZW1lbnQuc2V0QXR0cmlidXRlKFwiZW5jdHlwZVwiLCBcIm11bHRpcGFydC9mb3JtLWRhdGFcIik7XG4gICAgICAgIHRoaXMuZWxlbWVudC5zZXRBdHRyaWJ1dGUoXCJtZXRob2RcIiwgdGhpcy5vcHRpb25zLm1ldGhvZCk7XG4gICAgICB9XG4gICAgICByZXR1cm4gZm9ybSAhPSBudWxsID8gZm9ybSA6IGZpZWxkcztcbiAgICB9O1xuXG4gICAgRHJvcHpvbmUucHJvdG90eXBlLmdldEV4aXN0aW5nRmFsbGJhY2sgPSBmdW5jdGlvbigpIHtcbiAgICAgIHZhciBmYWxsYmFjaywgZ2V0RmFsbGJhY2ssIHRhZ05hbWUsIF9pLCBfbGVuLCBfcmVmO1xuICAgICAgZ2V0RmFsbGJhY2sgPSBmdW5jdGlvbihlbGVtZW50cykge1xuICAgICAgICB2YXIgZWwsIF9pLCBfbGVuO1xuICAgICAgICBmb3IgKF9pID0gMCwgX2xlbiA9IGVsZW1lbnRzLmxlbmd0aDsgX2kgPCBfbGVuOyBfaSsrKSB7XG4gICAgICAgICAgZWwgPSBlbGVtZW50c1tfaV07XG4gICAgICAgICAgaWYgKC8oXnwgKWZhbGxiYWNrKCR8ICkvLnRlc3QoZWwuY2xhc3NOYW1lKSkge1xuICAgICAgICAgICAgcmV0dXJuIGVsO1xuICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgfTtcbiAgICAgIF9yZWYgPSBbXCJkaXZcIiwgXCJmb3JtXCJdO1xuICAgICAgZm9yIChfaSA9IDAsIF9sZW4gPSBfcmVmLmxlbmd0aDsgX2kgPCBfbGVuOyBfaSsrKSB7XG4gICAgICAgIHRhZ05hbWUgPSBfcmVmW19pXTtcbiAgICAgICAgaWYgKGZhbGxiYWNrID0gZ2V0RmFsbGJhY2sodGhpcy5lbGVtZW50LmdldEVsZW1lbnRzQnlUYWdOYW1lKHRhZ05hbWUpKSkge1xuICAgICAgICAgIHJldHVybiBmYWxsYmFjaztcbiAgICAgICAgfVxuICAgICAgfVxuICAgIH07XG5cbiAgICBEcm9wem9uZS5wcm90b3R5cGUuc2V0dXBFdmVudExpc3RlbmVycyA9IGZ1bmN0aW9uKCkge1xuICAgICAgdmFyIGVsZW1lbnRMaXN0ZW5lcnMsIGV2ZW50LCBsaXN0ZW5lciwgX2ksIF9sZW4sIF9yZWYsIF9yZXN1bHRzO1xuICAgICAgX3JlZiA9IHRoaXMubGlzdGVuZXJzO1xuICAgICAgX3Jlc3VsdHMgPSBbXTtcbiAgICAgIGZvciAoX2kgPSAwLCBfbGVuID0gX3JlZi5sZW5ndGg7IF9pIDwgX2xlbjsgX2krKykge1xuICAgICAgICBlbGVtZW50TGlzdGVuZXJzID0gX3JlZltfaV07XG4gICAgICAgIF9yZXN1bHRzLnB1c2goKGZ1bmN0aW9uKCkge1xuICAgICAgICAgIHZhciBfcmVmMSwgX3Jlc3VsdHMxO1xuICAgICAgICAgIF9yZWYxID0gZWxlbWVudExpc3RlbmVycy5ldmVudHM7XG4gICAgICAgICAgX3Jlc3VsdHMxID0gW107XG4gICAgICAgICAgZm9yIChldmVudCBpbiBfcmVmMSkge1xuICAgICAgICAgICAgbGlzdGVuZXIgPSBfcmVmMVtldmVudF07XG4gICAgICAgICAgICBfcmVzdWx0czEucHVzaChlbGVtZW50TGlzdGVuZXJzLmVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihldmVudCwgbGlzdGVuZXIsIGZhbHNlKSk7XG4gICAgICAgICAgfVxuICAgICAgICAgIHJldHVybiBfcmVzdWx0czE7XG4gICAgICAgIH0pKCkpO1xuICAgICAgfVxuICAgICAgcmV0dXJuIF9yZXN1bHRzO1xuICAgIH07XG5cbiAgICBEcm9wem9uZS5wcm90b3R5cGUucmVtb3ZlRXZlbnRMaXN0ZW5lcnMgPSBmdW5jdGlvbigpIHtcbiAgICAgIHZhciBlbGVtZW50TGlzdGVuZXJzLCBldmVudCwgbGlzdGVuZXIsIF9pLCBfbGVuLCBfcmVmLCBfcmVzdWx0cztcbiAgICAgIF9yZWYgPSB0aGlzLmxpc3RlbmVycztcbiAgICAgIF9yZXN1bHRzID0gW107XG4gICAgICBmb3IgKF9pID0gMCwgX2xlbiA9IF9yZWYubGVuZ3RoOyBfaSA8IF9sZW47IF9pKyspIHtcbiAgICAgICAgZWxlbWVudExpc3RlbmVycyA9IF9yZWZbX2ldO1xuICAgICAgICBfcmVzdWx0cy5wdXNoKChmdW5jdGlvbigpIHtcbiAgICAgICAgICB2YXIgX3JlZjEsIF9yZXN1bHRzMTtcbiAgICAgICAgICBfcmVmMSA9IGVsZW1lbnRMaXN0ZW5lcnMuZXZlbnRzO1xuICAgICAgICAgIF9yZXN1bHRzMSA9IFtdO1xuICAgICAgICAgIGZvciAoZXZlbnQgaW4gX3JlZjEpIHtcbiAgICAgICAgICAgIGxpc3RlbmVyID0gX3JlZjFbZXZlbnRdO1xuICAgICAgICAgICAgX3Jlc3VsdHMxLnB1c2goZWxlbWVudExpc3RlbmVycy5lbGVtZW50LnJlbW92ZUV2ZW50TGlzdGVuZXIoZXZlbnQsIGxpc3RlbmVyLCBmYWxzZSkpO1xuICAgICAgICAgIH1cbiAgICAgICAgICByZXR1cm4gX3Jlc3VsdHMxO1xuICAgICAgICB9KSgpKTtcbiAgICAgIH1cbiAgICAgIHJldHVybiBfcmVzdWx0cztcbiAgICB9O1xuXG4gICAgRHJvcHpvbmUucHJvdG90eXBlLmRpc2FibGUgPSBmdW5jdGlvbigpIHtcbiAgICAgIHZhciBmaWxlLCBfaSwgX2xlbiwgX3JlZiwgX3Jlc3VsdHM7XG4gICAgICB0aGlzLmNsaWNrYWJsZUVsZW1lbnRzLmZvckVhY2goZnVuY3Rpb24oZWxlbWVudCkge1xuICAgICAgICByZXR1cm4gZWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKFwiZHotY2xpY2thYmxlXCIpO1xuICAgICAgfSk7XG4gICAgICB0aGlzLnJlbW92ZUV2ZW50TGlzdGVuZXJzKCk7XG4gICAgICBfcmVmID0gdGhpcy5maWxlcztcbiAgICAgIF9yZXN1bHRzID0gW107XG4gICAgICBmb3IgKF9pID0gMCwgX2xlbiA9IF9yZWYubGVuZ3RoOyBfaSA8IF9sZW47IF9pKyspIHtcbiAgICAgICAgZmlsZSA9IF9yZWZbX2ldO1xuICAgICAgICBfcmVzdWx0cy5wdXNoKHRoaXMuY2FuY2VsVXBsb2FkKGZpbGUpKTtcbiAgICAgIH1cbiAgICAgIHJldHVybiBfcmVzdWx0cztcbiAgICB9O1xuXG4gICAgRHJvcHpvbmUucHJvdG90eXBlLmVuYWJsZSA9IGZ1bmN0aW9uKCkge1xuICAgICAgdGhpcy5jbGlja2FibGVFbGVtZW50cy5mb3JFYWNoKGZ1bmN0aW9uKGVsZW1lbnQpIHtcbiAgICAgICAgcmV0dXJuIGVsZW1lbnQuY2xhc3NMaXN0LmFkZChcImR6LWNsaWNrYWJsZVwiKTtcbiAgICAgIH0pO1xuICAgICAgcmV0dXJuIHRoaXMuc2V0dXBFdmVudExpc3RlbmVycygpO1xuICAgIH07XG5cbiAgICBEcm9wem9uZS5wcm90b3R5cGUuZmlsZXNpemUgPSBmdW5jdGlvbihzaXplKSB7XG4gICAgICB2YXIgY3V0b2ZmLCBpLCBzZWxlY3RlZFNpemUsIHNlbGVjdGVkVW5pdCwgdW5pdCwgdW5pdHMsIF9pLCBfbGVuO1xuICAgICAgc2VsZWN0ZWRTaXplID0gMDtcbiAgICAgIHNlbGVjdGVkVW5pdCA9IFwiYlwiO1xuICAgICAgaWYgKHNpemUgPiAwKSB7XG4gICAgICAgIHVuaXRzID0gWydUQicsICdHQicsICdNQicsICdLQicsICdiJ107XG4gICAgICAgIGZvciAoaSA9IF9pID0gMCwgX2xlbiA9IHVuaXRzLmxlbmd0aDsgX2kgPCBfbGVuOyBpID0gKytfaSkge1xuICAgICAgICAgIHVuaXQgPSB1bml0c1tpXTtcbiAgICAgICAgICBjdXRvZmYgPSBNYXRoLnBvdyh0aGlzLm9wdGlvbnMuZmlsZXNpemVCYXNlLCA0IC0gaSkgLyAxMDtcbiAgICAgICAgICBpZiAoc2l6ZSA+PSBjdXRvZmYpIHtcbiAgICAgICAgICAgIHNlbGVjdGVkU2l6ZSA9IHNpemUgLyBNYXRoLnBvdyh0aGlzLm9wdGlvbnMuZmlsZXNpemVCYXNlLCA0IC0gaSk7XG4gICAgICAgICAgICBzZWxlY3RlZFVuaXQgPSB1bml0O1xuICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIHNlbGVjdGVkU2l6ZSA9IE1hdGgucm91bmQoMTAgKiBzZWxlY3RlZFNpemUpIC8gMTA7XG4gICAgICB9XG4gICAgICByZXR1cm4gXCI8c3Ryb25nPlwiICsgc2VsZWN0ZWRTaXplICsgXCI8L3N0cm9uZz4gXCIgKyBzZWxlY3RlZFVuaXQ7XG4gICAgfTtcblxuICAgIERyb3B6b25lLnByb3RvdHlwZS5fdXBkYXRlTWF4RmlsZXNSZWFjaGVkQ2xhc3MgPSBmdW5jdGlvbigpIHtcbiAgICAgIGlmICgodGhpcy5vcHRpb25zLm1heEZpbGVzICE9IG51bGwpICYmIHRoaXMuZ2V0QWNjZXB0ZWRGaWxlcygpLmxlbmd0aCA+PSB0aGlzLm9wdGlvbnMubWF4RmlsZXMpIHtcbiAgICAgICAgaWYgKHRoaXMuZ2V0QWNjZXB0ZWRGaWxlcygpLmxlbmd0aCA9PT0gdGhpcy5vcHRpb25zLm1heEZpbGVzKSB7XG4gICAgICAgICAgdGhpcy5lbWl0KCdtYXhmaWxlc3JlYWNoZWQnLCB0aGlzLmZpbGVzKTtcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoXCJkei1tYXgtZmlsZXMtcmVhY2hlZFwiKTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIHJldHVybiB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZShcImR6LW1heC1maWxlcy1yZWFjaGVkXCIpO1xuICAgICAgfVxuICAgIH07XG5cbiAgICBEcm9wem9uZS5wcm90b3R5cGUuZHJvcCA9IGZ1bmN0aW9uKGUpIHtcbiAgICAgIHZhciBmaWxlcywgaXRlbXM7XG4gICAgICBpZiAoIWUuZGF0YVRyYW5zZmVyKSB7XG4gICAgICAgIHJldHVybjtcbiAgICAgIH1cbiAgICAgIHRoaXMuZW1pdChcImRyb3BcIiwgZSk7XG4gICAgICBmaWxlcyA9IGUuZGF0YVRyYW5zZmVyLmZpbGVzO1xuICAgICAgdGhpcy5lbWl0KFwiYWRkZWRmaWxlc1wiLCBmaWxlcyk7XG4gICAgICBpZiAoZmlsZXMubGVuZ3RoKSB7XG4gICAgICAgIGl0ZW1zID0gZS5kYXRhVHJhbnNmZXIuaXRlbXM7XG4gICAgICAgIGlmIChpdGVtcyAmJiBpdGVtcy5sZW5ndGggJiYgKGl0ZW1zWzBdLndlYmtpdEdldEFzRW50cnkgIT0gbnVsbCkpIHtcbiAgICAgICAgICB0aGlzLl9hZGRGaWxlc0Zyb21JdGVtcyhpdGVtcyk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgdGhpcy5oYW5kbGVGaWxlcyhmaWxlcyk7XG4gICAgICAgIH1cbiAgICAgIH1cbiAgICB9O1xuXG4gICAgRHJvcHpvbmUucHJvdG90eXBlLnBhc3RlID0gZnVuY3Rpb24oZSkge1xuICAgICAgdmFyIGl0ZW1zLCBfcmVmO1xuICAgICAgaWYgKChlICE9IG51bGwgPyAoX3JlZiA9IGUuY2xpcGJvYXJkRGF0YSkgIT0gbnVsbCA/IF9yZWYuaXRlbXMgOiB2b2lkIDAgOiB2b2lkIDApID09IG51bGwpIHtcbiAgICAgICAgcmV0dXJuO1xuICAgICAgfVxuICAgICAgdGhpcy5lbWl0KFwicGFzdGVcIiwgZSk7XG4gICAgICBpdGVtcyA9IGUuY2xpcGJvYXJkRGF0YS5pdGVtcztcbiAgICAgIGlmIChpdGVtcy5sZW5ndGgpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuX2FkZEZpbGVzRnJvbUl0ZW1zKGl0ZW1zKTtcbiAgICAgIH1cbiAgICB9O1xuXG4gICAgRHJvcHpvbmUucHJvdG90eXBlLmhhbmRsZUZpbGVzID0gZnVuY3Rpb24oZmlsZXMpIHtcbiAgICAgIHZhciBmaWxlLCBfaSwgX2xlbiwgX3Jlc3VsdHM7XG4gICAgICBfcmVzdWx0cyA9IFtdO1xuICAgICAgZm9yIChfaSA9IDAsIF9sZW4gPSBmaWxlcy5sZW5ndGg7IF9pIDwgX2xlbjsgX2krKykge1xuICAgICAgICBmaWxlID0gZmlsZXNbX2ldO1xuICAgICAgICBfcmVzdWx0cy5wdXNoKHRoaXMuYWRkRmlsZShmaWxlKSk7XG4gICAgICB9XG4gICAgICByZXR1cm4gX3Jlc3VsdHM7XG4gICAgfTtcblxuICAgIERyb3B6b25lLnByb3RvdHlwZS5fYWRkRmlsZXNGcm9tSXRlbXMgPSBmdW5jdGlvbihpdGVtcykge1xuICAgICAgdmFyIGVudHJ5LCBpdGVtLCBfaSwgX2xlbiwgX3Jlc3VsdHM7XG4gICAgICBfcmVzdWx0cyA9IFtdO1xuICAgICAgZm9yIChfaSA9IDAsIF9sZW4gPSBpdGVtcy5sZW5ndGg7IF9pIDwgX2xlbjsgX2krKykge1xuICAgICAgICBpdGVtID0gaXRlbXNbX2ldO1xuICAgICAgICBpZiAoKGl0ZW0ud2Via2l0R2V0QXNFbnRyeSAhPSBudWxsKSAmJiAoZW50cnkgPSBpdGVtLndlYmtpdEdldEFzRW50cnkoKSkpIHtcbiAgICAgICAgICBpZiAoZW50cnkuaXNGaWxlKSB7XG4gICAgICAgICAgICBfcmVzdWx0cy5wdXNoKHRoaXMuYWRkRmlsZShpdGVtLmdldEFzRmlsZSgpKSk7XG4gICAgICAgICAgfSBlbHNlIGlmIChlbnRyeS5pc0RpcmVjdG9yeSkge1xuICAgICAgICAgICAgX3Jlc3VsdHMucHVzaCh0aGlzLl9hZGRGaWxlc0Zyb21EaXJlY3RvcnkoZW50cnksIGVudHJ5Lm5hbWUpKTtcbiAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgX3Jlc3VsdHMucHVzaCh2b2lkIDApO1xuICAgICAgICAgIH1cbiAgICAgICAgfSBlbHNlIGlmIChpdGVtLmdldEFzRmlsZSAhPSBudWxsKSB7XG4gICAgICAgICAgaWYgKChpdGVtLmtpbmQgPT0gbnVsbCkgfHwgaXRlbS5raW5kID09PSBcImZpbGVcIikge1xuICAgICAgICAgICAgX3Jlc3VsdHMucHVzaCh0aGlzLmFkZEZpbGUoaXRlbS5nZXRBc0ZpbGUoKSkpO1xuICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBfcmVzdWx0cy5wdXNoKHZvaWQgMCk7XG4gICAgICAgICAgfVxuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIF9yZXN1bHRzLnB1c2godm9pZCAwKTtcbiAgICAgICAgfVxuICAgICAgfVxuICAgICAgcmV0dXJuIF9yZXN1bHRzO1xuICAgIH07XG5cbiAgICBEcm9wem9uZS5wcm90b3R5cGUuX2FkZEZpbGVzRnJvbURpcmVjdG9yeSA9IGZ1bmN0aW9uKGRpcmVjdG9yeSwgcGF0aCkge1xuICAgICAgdmFyIGRpclJlYWRlciwgZXJyb3JIYW5kbGVyLCByZWFkRW50cmllcztcbiAgICAgIGRpclJlYWRlciA9IGRpcmVjdG9yeS5jcmVhdGVSZWFkZXIoKTtcbiAgICAgIGVycm9ySGFuZGxlciA9IGZ1bmN0aW9uKGVycm9yKSB7XG4gICAgICAgIHJldHVybiB0eXBlb2YgY29uc29sZSAhPT0gXCJ1bmRlZmluZWRcIiAmJiBjb25zb2xlICE9PSBudWxsID8gdHlwZW9mIGNvbnNvbGUubG9nID09PSBcImZ1bmN0aW9uXCIgPyBjb25zb2xlLmxvZyhlcnJvcikgOiB2b2lkIDAgOiB2b2lkIDA7XG4gICAgICB9O1xuICAgICAgcmVhZEVudHJpZXMgPSAoZnVuY3Rpb24oX3RoaXMpIHtcbiAgICAgICAgcmV0dXJuIGZ1bmN0aW9uKCkge1xuICAgICAgICAgIHJldHVybiBkaXJSZWFkZXIucmVhZEVudHJpZXMoZnVuY3Rpb24oZW50cmllcykge1xuICAgICAgICAgICAgdmFyIGVudHJ5LCBfaSwgX2xlbjtcbiAgICAgICAgICAgIGlmIChlbnRyaWVzLmxlbmd0aCA+IDApIHtcbiAgICAgICAgICAgICAgZm9yIChfaSA9IDAsIF9sZW4gPSBlbnRyaWVzLmxlbmd0aDsgX2kgPCBfbGVuOyBfaSsrKSB7XG4gICAgICAgICAgICAgICAgZW50cnkgPSBlbnRyaWVzW19pXTtcbiAgICAgICAgICAgICAgICBpZiAoZW50cnkuaXNGaWxlKSB7XG4gICAgICAgICAgICAgICAgICBlbnRyeS5maWxlKGZ1bmN0aW9uKGZpbGUpIHtcbiAgICAgICAgICAgICAgICAgICAgaWYgKF90aGlzLm9wdGlvbnMuaWdub3JlSGlkZGVuRmlsZXMgJiYgZmlsZS5uYW1lLnN1YnN0cmluZygwLCAxKSA9PT0gJy4nKSB7XG4gICAgICAgICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIGZpbGUuZnVsbFBhdGggPSBcIlwiICsgcGF0aCArIFwiL1wiICsgZmlsZS5uYW1lO1xuICAgICAgICAgICAgICAgICAgICByZXR1cm4gX3RoaXMuYWRkRmlsZShmaWxlKTtcbiAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiAoZW50cnkuaXNEaXJlY3RvcnkpIHtcbiAgICAgICAgICAgICAgICAgIF90aGlzLl9hZGRGaWxlc0Zyb21EaXJlY3RvcnkoZW50cnksIFwiXCIgKyBwYXRoICsgXCIvXCIgKyBlbnRyeS5uYW1lKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgcmVhZEVudHJpZXMoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybiBudWxsO1xuICAgICAgICAgIH0sIGVycm9ySGFuZGxlcik7XG4gICAgICAgIH07XG4gICAgICB9KSh0aGlzKTtcbiAgICAgIHJldHVybiByZWFkRW50cmllcygpO1xuICAgIH07XG5cbiAgICBEcm9wem9uZS5wcm90b3R5cGUuYWNjZXB0ID0gZnVuY3Rpb24oZmlsZSwgZG9uZSkge1xuICAgICAgaWYgKGZpbGUuc2l6ZSA+IHRoaXMub3B0aW9ucy5tYXhGaWxlc2l6ZSAqIDEwMjQgKiAxMDI0KSB7XG4gICAgICAgIHJldHVybiBkb25lKHRoaXMub3B0aW9ucy5kaWN0RmlsZVRvb0JpZy5yZXBsYWNlKFwie3tmaWxlc2l6ZX19XCIsIE1hdGgucm91bmQoZmlsZS5zaXplIC8gMTAyNCAvIDEwLjI0KSAvIDEwMCkucmVwbGFjZShcInt7bWF4RmlsZXNpemV9fVwiLCB0aGlzLm9wdGlvbnMubWF4RmlsZXNpemUpKTtcbiAgICAgIH0gZWxzZSBpZiAoIURyb3B6b25lLmlzVmFsaWRGaWxlKGZpbGUsIHRoaXMub3B0aW9ucy5hY2NlcHRlZEZpbGVzKSkge1xuICAgICAgICByZXR1cm4gZG9uZSh0aGlzLm9wdGlvbnMuZGljdEludmFsaWRGaWxlVHlwZSk7XG4gICAgICB9IGVsc2UgaWYgKCh0aGlzLm9wdGlvbnMubWF4RmlsZXMgIT0gbnVsbCkgJiYgdGhpcy5nZXRBY2NlcHRlZEZpbGVzKCkubGVuZ3RoID49IHRoaXMub3B0aW9ucy5tYXhGaWxlcykge1xuICAgICAgICBkb25lKHRoaXMub3B0aW9ucy5kaWN0TWF4RmlsZXNFeGNlZWRlZC5yZXBsYWNlKFwie3ttYXhGaWxlc319XCIsIHRoaXMub3B0aW9ucy5tYXhGaWxlcykpO1xuICAgICAgICByZXR1cm4gdGhpcy5lbWl0KFwibWF4ZmlsZXNleGNlZWRlZFwiLCBmaWxlKTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIHJldHVybiB0aGlzLm9wdGlvbnMuYWNjZXB0LmNhbGwodGhpcywgZmlsZSwgZG9uZSk7XG4gICAgICB9XG4gICAgfTtcblxuICAgIERyb3B6b25lLnByb3RvdHlwZS5hZGRGaWxlID0gZnVuY3Rpb24oZmlsZSkge1xuICAgICAgZmlsZS51cGxvYWQgPSB7XG4gICAgICAgIHByb2dyZXNzOiAwLFxuICAgICAgICB0b3RhbDogZmlsZS5zaXplLFxuICAgICAgICBieXRlc1NlbnQ6IDBcbiAgICAgIH07XG4gICAgICB0aGlzLmZpbGVzLnB1c2goZmlsZSk7XG4gICAgICBmaWxlLnN0YXR1cyA9IERyb3B6b25lLkFEREVEO1xuICAgICAgdGhpcy5lbWl0KFwiYWRkZWRmaWxlXCIsIGZpbGUpO1xuICAgICAgdGhpcy5fZW5xdWV1ZVRodW1ibmFpbChmaWxlKTtcbiAgICAgIHJldHVybiB0aGlzLmFjY2VwdChmaWxlLCAoZnVuY3Rpb24oX3RoaXMpIHtcbiAgICAgICAgcmV0dXJuIGZ1bmN0aW9uKGVycm9yKSB7XG4gICAgICAgICAgaWYgKGVycm9yKSB7XG4gICAgICAgICAgICBmaWxlLmFjY2VwdGVkID0gZmFsc2U7XG4gICAgICAgICAgICBfdGhpcy5fZXJyb3JQcm9jZXNzaW5nKFtmaWxlXSwgZXJyb3IpO1xuICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBmaWxlLmFjY2VwdGVkID0gdHJ1ZTtcbiAgICAgICAgICAgIGlmIChfdGhpcy5vcHRpb25zLmF1dG9RdWV1ZSkge1xuICAgICAgICAgICAgICBfdGhpcy5lbnF1ZXVlRmlsZShmaWxlKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9XG4gICAgICAgICAgcmV0dXJuIF90aGlzLl91cGRhdGVNYXhGaWxlc1JlYWNoZWRDbGFzcygpO1xuICAgICAgICB9O1xuICAgICAgfSkodGhpcykpO1xuICAgIH07XG5cbiAgICBEcm9wem9uZS5wcm90b3R5cGUuZW5xdWV1ZUZpbGVzID0gZnVuY3Rpb24oZmlsZXMpIHtcbiAgICAgIHZhciBmaWxlLCBfaSwgX2xlbjtcbiAgICAgIGZvciAoX2kgPSAwLCBfbGVuID0gZmlsZXMubGVuZ3RoOyBfaSA8IF9sZW47IF9pKyspIHtcbiAgICAgICAgZmlsZSA9IGZpbGVzW19pXTtcbiAgICAgICAgdGhpcy5lbnF1ZXVlRmlsZShmaWxlKTtcbiAgICAgIH1cbiAgICAgIHJldHVybiBudWxsO1xuICAgIH07XG5cbiAgICBEcm9wem9uZS5wcm90b3R5cGUuZW5xdWV1ZUZpbGUgPSBmdW5jdGlvbihmaWxlKSB7XG4gICAgICBpZiAoZmlsZS5zdGF0dXMgPT09IERyb3B6b25lLkFEREVEICYmIGZpbGUuYWNjZXB0ZWQgPT09IHRydWUpIHtcbiAgICAgICAgZmlsZS5zdGF0dXMgPSBEcm9wem9uZS5RVUVVRUQ7XG4gICAgICAgIGlmICh0aGlzLm9wdGlvbnMuYXV0b1Byb2Nlc3NRdWV1ZSkge1xuICAgICAgICAgIHJldHVybiBzZXRUaW1lb3V0KCgoZnVuY3Rpb24oX3RoaXMpIHtcbiAgICAgICAgICAgIHJldHVybiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgcmV0dXJuIF90aGlzLnByb2Nlc3NRdWV1ZSgpO1xuICAgICAgICAgICAgfTtcbiAgICAgICAgICB9KSh0aGlzKSksIDApO1xuICAgICAgICB9XG4gICAgICB9IGVsc2Uge1xuICAgICAgICB0aHJvdyBuZXcgRXJyb3IoXCJUaGlzIGZpbGUgY2FuJ3QgYmUgcXVldWVkIGJlY2F1c2UgaXQgaGFzIGFscmVhZHkgYmVlbiBwcm9jZXNzZWQgb3Igd2FzIHJlamVjdGVkLlwiKTtcbiAgICAgIH1cbiAgICB9O1xuXG4gICAgRHJvcHpvbmUucHJvdG90eXBlLl90aHVtYm5haWxRdWV1ZSA9IFtdO1xuXG4gICAgRHJvcHpvbmUucHJvdG90eXBlLl9wcm9jZXNzaW5nVGh1bWJuYWlsID0gZmFsc2U7XG5cbiAgICBEcm9wem9uZS5wcm90b3R5cGUuX2VucXVldWVUaHVtYm5haWwgPSBmdW5jdGlvbihmaWxlKSB7XG4gICAgICBpZiAodGhpcy5vcHRpb25zLmNyZWF0ZUltYWdlVGh1bWJuYWlscyAmJiBmaWxlLnR5cGUubWF0Y2goL2ltYWdlLiovKSAmJiBmaWxlLnNpemUgPD0gdGhpcy5vcHRpb25zLm1heFRodW1ibmFpbEZpbGVzaXplICogMTAyNCAqIDEwMjQpIHtcbiAgICAgICAgdGhpcy5fdGh1bWJuYWlsUXVldWUucHVzaChmaWxlKTtcbiAgICAgICAgcmV0dXJuIHNldFRpbWVvdXQoKChmdW5jdGlvbihfdGhpcykge1xuICAgICAgICAgIHJldHVybiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIHJldHVybiBfdGhpcy5fcHJvY2Vzc1RodW1ibmFpbFF1ZXVlKCk7XG4gICAgICAgICAgfTtcbiAgICAgICAgfSkodGhpcykpLCAwKTtcbiAgICAgIH1cbiAgICB9O1xuXG4gICAgRHJvcHpvbmUucHJvdG90eXBlLl9wcm9jZXNzVGh1bWJuYWlsUXVldWUgPSBmdW5jdGlvbigpIHtcbiAgICAgIGlmICh0aGlzLl9wcm9jZXNzaW5nVGh1bWJuYWlsIHx8IHRoaXMuX3RodW1ibmFpbFF1ZXVlLmxlbmd0aCA9PT0gMCkge1xuICAgICAgICByZXR1cm47XG4gICAgICB9XG4gICAgICB0aGlzLl9wcm9jZXNzaW5nVGh1bWJuYWlsID0gdHJ1ZTtcbiAgICAgIHJldHVybiB0aGlzLmNyZWF0ZVRodW1ibmFpbCh0aGlzLl90aHVtYm5haWxRdWV1ZS5zaGlmdCgpLCAoZnVuY3Rpb24oX3RoaXMpIHtcbiAgICAgICAgcmV0dXJuIGZ1bmN0aW9uKCkge1xuICAgICAgICAgIF90aGlzLl9wcm9jZXNzaW5nVGh1bWJuYWlsID0gZmFsc2U7XG4gICAgICAgICAgcmV0dXJuIF90aGlzLl9wcm9jZXNzVGh1bWJuYWlsUXVldWUoKTtcbiAgICAgICAgfTtcbiAgICAgIH0pKHRoaXMpKTtcbiAgICB9O1xuXG4gICAgRHJvcHpvbmUucHJvdG90eXBlLnJlbW92ZUZpbGUgPSBmdW5jdGlvbihmaWxlKSB7XG4gICAgICBpZiAoZmlsZS5zdGF0dXMgPT09IERyb3B6b25lLlVQTE9BRElORykge1xuICAgICAgICB0aGlzLmNhbmNlbFVwbG9hZChmaWxlKTtcbiAgICAgIH1cbiAgICAgIHRoaXMuZmlsZXMgPSB3aXRob3V0KHRoaXMuZmlsZXMsIGZpbGUpO1xuICAgICAgdGhpcy5lbWl0KFwicmVtb3ZlZGZpbGVcIiwgZmlsZSk7XG4gICAgICBpZiAodGhpcy5maWxlcy5sZW5ndGggPT09IDApIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuZW1pdChcInJlc2V0XCIpO1xuICAgICAgfVxuICAgIH07XG5cbiAgICBEcm9wem9uZS5wcm90b3R5cGUucmVtb3ZlQWxsRmlsZXMgPSBmdW5jdGlvbihjYW5jZWxJZk5lY2Vzc2FyeSkge1xuICAgICAgdmFyIGZpbGUsIF9pLCBfbGVuLCBfcmVmO1xuICAgICAgaWYgKGNhbmNlbElmTmVjZXNzYXJ5ID09IG51bGwpIHtcbiAgICAgICAgY2FuY2VsSWZOZWNlc3NhcnkgPSBmYWxzZTtcbiAgICAgIH1cbiAgICAgIF9yZWYgPSB0aGlzLmZpbGVzLnNsaWNlKCk7XG4gICAgICBmb3IgKF9pID0gMCwgX2xlbiA9IF9yZWYubGVuZ3RoOyBfaSA8IF9sZW47IF9pKyspIHtcbiAgICAgICAgZmlsZSA9IF9yZWZbX2ldO1xuICAgICAgICBpZiAoZmlsZS5zdGF0dXMgIT09IERyb3B6b25lLlVQTE9BRElORyB8fCBjYW5jZWxJZk5lY2Vzc2FyeSkge1xuICAgICAgICAgIHRoaXMucmVtb3ZlRmlsZShmaWxlKTtcbiAgICAgICAgfVxuICAgICAgfVxuICAgICAgcmV0dXJuIG51bGw7XG4gICAgfTtcblxuICAgIERyb3B6b25lLnByb3RvdHlwZS5jcmVhdGVUaHVtYm5haWwgPSBmdW5jdGlvbihmaWxlLCBjYWxsYmFjaykge1xuICAgICAgdmFyIGZpbGVSZWFkZXI7XG4gICAgICBmaWxlUmVhZGVyID0gbmV3IEZpbGVSZWFkZXI7XG4gICAgICBmaWxlUmVhZGVyLm9ubG9hZCA9IChmdW5jdGlvbihfdGhpcykge1xuICAgICAgICByZXR1cm4gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgaWYgKGZpbGUudHlwZSA9PT0gXCJpbWFnZS9zdmcreG1sXCIpIHtcbiAgICAgICAgICAgIF90aGlzLmVtaXQoXCJ0aHVtYm5haWxcIiwgZmlsZSwgZmlsZVJlYWRlci5yZXN1bHQpO1xuICAgICAgICAgICAgaWYgKGNhbGxiYWNrICE9IG51bGwpIHtcbiAgICAgICAgICAgICAgY2FsbGJhY2soKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICB9XG4gICAgICAgICAgcmV0dXJuIF90aGlzLmNyZWF0ZVRodW1ibmFpbEZyb21VcmwoZmlsZSwgZmlsZVJlYWRlci5yZXN1bHQsIGNhbGxiYWNrKTtcbiAgICAgICAgfTtcbiAgICAgIH0pKHRoaXMpO1xuICAgICAgcmV0dXJuIGZpbGVSZWFkZXIucmVhZEFzRGF0YVVSTChmaWxlKTtcbiAgICB9O1xuXG4gICAgRHJvcHpvbmUucHJvdG90eXBlLmNyZWF0ZVRodW1ibmFpbEZyb21VcmwgPSBmdW5jdGlvbihmaWxlLCBpbWFnZVVybCwgY2FsbGJhY2ssIGNyb3NzT3JpZ2luKSB7XG4gICAgICB2YXIgaW1nO1xuICAgICAgaW1nID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImltZ1wiKTtcbiAgICAgIGlmIChjcm9zc09yaWdpbikge1xuICAgICAgICBpbWcuY3Jvc3NPcmlnaW4gPSBjcm9zc09yaWdpbjtcbiAgICAgIH1cbiAgICAgIGltZy5vbmxvYWQgPSAoZnVuY3Rpb24oX3RoaXMpIHtcbiAgICAgICAgcmV0dXJuIGZ1bmN0aW9uKCkge1xuICAgICAgICAgIHZhciBjYW52YXMsIGN0eCwgcmVzaXplSW5mbywgdGh1bWJuYWlsLCBfcmVmLCBfcmVmMSwgX3JlZjIsIF9yZWYzO1xuICAgICAgICAgIGZpbGUud2lkdGggPSBpbWcud2lkdGg7XG4gICAgICAgICAgZmlsZS5oZWlnaHQgPSBpbWcuaGVpZ2h0O1xuICAgICAgICAgIHJlc2l6ZUluZm8gPSBfdGhpcy5vcHRpb25zLnJlc2l6ZS5jYWxsKF90aGlzLCBmaWxlKTtcbiAgICAgICAgICBpZiAocmVzaXplSW5mby50cmdXaWR0aCA9PSBudWxsKSB7XG4gICAgICAgICAgICByZXNpemVJbmZvLnRyZ1dpZHRoID0gcmVzaXplSW5mby5vcHRXaWR0aDtcbiAgICAgICAgICB9XG4gICAgICAgICAgaWYgKHJlc2l6ZUluZm8udHJnSGVpZ2h0ID09IG51bGwpIHtcbiAgICAgICAgICAgIHJlc2l6ZUluZm8udHJnSGVpZ2h0ID0gcmVzaXplSW5mby5vcHRIZWlnaHQ7XG4gICAgICAgICAgfVxuICAgICAgICAgIGNhbnZhcyA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJjYW52YXNcIik7XG4gICAgICAgICAgY3R4ID0gY2FudmFzLmdldENvbnRleHQoXCIyZFwiKTtcbiAgICAgICAgICBjYW52YXMud2lkdGggPSByZXNpemVJbmZvLnRyZ1dpZHRoO1xuICAgICAgICAgIGNhbnZhcy5oZWlnaHQgPSByZXNpemVJbmZvLnRyZ0hlaWdodDtcbiAgICAgICAgICBkcmF3SW1hZ2VJT1NGaXgoY3R4LCBpbWcsIChfcmVmID0gcmVzaXplSW5mby5zcmNYKSAhPSBudWxsID8gX3JlZiA6IDAsIChfcmVmMSA9IHJlc2l6ZUluZm8uc3JjWSkgIT0gbnVsbCA/IF9yZWYxIDogMCwgcmVzaXplSW5mby5zcmNXaWR0aCwgcmVzaXplSW5mby5zcmNIZWlnaHQsIChfcmVmMiA9IHJlc2l6ZUluZm8udHJnWCkgIT0gbnVsbCA/IF9yZWYyIDogMCwgKF9yZWYzID0gcmVzaXplSW5mby50cmdZKSAhPSBudWxsID8gX3JlZjMgOiAwLCByZXNpemVJbmZvLnRyZ1dpZHRoLCByZXNpemVJbmZvLnRyZ0hlaWdodCk7XG4gICAgICAgICAgdGh1bWJuYWlsID0gY2FudmFzLnRvRGF0YVVSTChcImltYWdlL3BuZ1wiKTtcbiAgICAgICAgICBfdGhpcy5lbWl0KFwidGh1bWJuYWlsXCIsIGZpbGUsIHRodW1ibmFpbCk7XG4gICAgICAgICAgaWYgKGNhbGxiYWNrICE9IG51bGwpIHtcbiAgICAgICAgICAgIHJldHVybiBjYWxsYmFjaygpO1xuICAgICAgICAgIH1cbiAgICAgICAgfTtcbiAgICAgIH0pKHRoaXMpO1xuICAgICAgaWYgKGNhbGxiYWNrICE9IG51bGwpIHtcbiAgICAgICAgaW1nLm9uZXJyb3IgPSBjYWxsYmFjaztcbiAgICAgIH1cbiAgICAgIHJldHVybiBpbWcuc3JjID0gaW1hZ2VVcmw7XG4gICAgfTtcblxuICAgIERyb3B6b25lLnByb3RvdHlwZS5wcm9jZXNzUXVldWUgPSBmdW5jdGlvbigpIHtcbiAgICAgIHZhciBpLCBwYXJhbGxlbFVwbG9hZHMsIHByb2Nlc3NpbmdMZW5ndGgsIHF1ZXVlZEZpbGVzO1xuICAgICAgcGFyYWxsZWxVcGxvYWRzID0gdGhpcy5vcHRpb25zLnBhcmFsbGVsVXBsb2FkcztcbiAgICAgIHByb2Nlc3NpbmdMZW5ndGggPSB0aGlzLmdldFVwbG9hZGluZ0ZpbGVzKCkubGVuZ3RoO1xuICAgICAgaSA9IHByb2Nlc3NpbmdMZW5ndGg7XG4gICAgICBpZiAocHJvY2Vzc2luZ0xlbmd0aCA+PSBwYXJhbGxlbFVwbG9hZHMpIHtcbiAgICAgICAgcmV0dXJuO1xuICAgICAgfVxuICAgICAgcXVldWVkRmlsZXMgPSB0aGlzLmdldFF1ZXVlZEZpbGVzKCk7XG4gICAgICBpZiAoIShxdWV1ZWRGaWxlcy5sZW5ndGggPiAwKSkge1xuICAgICAgICByZXR1cm47XG4gICAgICB9XG4gICAgICBpZiAodGhpcy5vcHRpb25zLnVwbG9hZE11bHRpcGxlKSB7XG4gICAgICAgIHJldHVybiB0aGlzLnByb2Nlc3NGaWxlcyhxdWV1ZWRGaWxlcy5zbGljZSgwLCBwYXJhbGxlbFVwbG9hZHMgLSBwcm9jZXNzaW5nTGVuZ3RoKSk7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICB3aGlsZSAoaSA8IHBhcmFsbGVsVXBsb2Fkcykge1xuICAgICAgICAgIGlmICghcXVldWVkRmlsZXMubGVuZ3RoKSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgfVxuICAgICAgICAgIHRoaXMucHJvY2Vzc0ZpbGUocXVldWVkRmlsZXMuc2hpZnQoKSk7XG4gICAgICAgICAgaSsrO1xuICAgICAgICB9XG4gICAgICB9XG4gICAgfTtcblxuICAgIERyb3B6b25lLnByb3RvdHlwZS5wcm9jZXNzRmlsZSA9IGZ1bmN0aW9uKGZpbGUpIHtcbiAgICAgIHJldHVybiB0aGlzLnByb2Nlc3NGaWxlcyhbZmlsZV0pO1xuICAgIH07XG5cbiAgICBEcm9wem9uZS5wcm90b3R5cGUucHJvY2Vzc0ZpbGVzID0gZnVuY3Rpb24oZmlsZXMpIHtcbiAgICAgIHZhciBmaWxlLCBfaSwgX2xlbjtcbiAgICAgIGZvciAoX2kgPSAwLCBfbGVuID0gZmlsZXMubGVuZ3RoOyBfaSA8IF9sZW47IF9pKyspIHtcbiAgICAgICAgZmlsZSA9IGZpbGVzW19pXTtcbiAgICAgICAgZmlsZS5wcm9jZXNzaW5nID0gdHJ1ZTtcbiAgICAgICAgZmlsZS5zdGF0dXMgPSBEcm9wem9uZS5VUExPQURJTkc7XG4gICAgICAgIHRoaXMuZW1pdChcInByb2Nlc3NpbmdcIiwgZmlsZSk7XG4gICAgICB9XG4gICAgICBpZiAodGhpcy5vcHRpb25zLnVwbG9hZE11bHRpcGxlKSB7XG4gICAgICAgIHRoaXMuZW1pdChcInByb2Nlc3NpbmdtdWx0aXBsZVwiLCBmaWxlcyk7XG4gICAgICB9XG4gICAgICByZXR1cm4gdGhpcy51cGxvYWRGaWxlcyhmaWxlcyk7XG4gICAgfTtcblxuICAgIERyb3B6b25lLnByb3RvdHlwZS5fZ2V0RmlsZXNXaXRoWGhyID0gZnVuY3Rpb24oeGhyKSB7XG4gICAgICB2YXIgZmlsZSwgZmlsZXM7XG4gICAgICByZXR1cm4gZmlsZXMgPSAoZnVuY3Rpb24oKSB7XG4gICAgICAgIHZhciBfaSwgX2xlbiwgX3JlZiwgX3Jlc3VsdHM7XG4gICAgICAgIF9yZWYgPSB0aGlzLmZpbGVzO1xuICAgICAgICBfcmVzdWx0cyA9IFtdO1xuICAgICAgICBmb3IgKF9pID0gMCwgX2xlbiA9IF9yZWYubGVuZ3RoOyBfaSA8IF9sZW47IF9pKyspIHtcbiAgICAgICAgICBmaWxlID0gX3JlZltfaV07XG4gICAgICAgICAgaWYgKGZpbGUueGhyID09PSB4aHIpIHtcbiAgICAgICAgICAgIF9yZXN1bHRzLnB1c2goZmlsZSk7XG4gICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIHJldHVybiBfcmVzdWx0cztcbiAgICAgIH0pLmNhbGwodGhpcyk7XG4gICAgfTtcblxuICAgIERyb3B6b25lLnByb3RvdHlwZS5jYW5jZWxVcGxvYWQgPSBmdW5jdGlvbihmaWxlKSB7XG4gICAgICB2YXIgZ3JvdXBlZEZpbGUsIGdyb3VwZWRGaWxlcywgX2ksIF9qLCBfbGVuLCBfbGVuMSwgX3JlZjtcbiAgICAgIGlmIChmaWxlLnN0YXR1cyA9PT0gRHJvcHpvbmUuVVBMT0FESU5HKSB7XG4gICAgICAgIGdyb3VwZWRGaWxlcyA9IHRoaXMuX2dldEZpbGVzV2l0aFhocihmaWxlLnhocik7XG4gICAgICAgIGZvciAoX2kgPSAwLCBfbGVuID0gZ3JvdXBlZEZpbGVzLmxlbmd0aDsgX2kgPCBfbGVuOyBfaSsrKSB7XG4gICAgICAgICAgZ3JvdXBlZEZpbGUgPSBncm91cGVkRmlsZXNbX2ldO1xuICAgICAgICAgIGdyb3VwZWRGaWxlLnN0YXR1cyA9IERyb3B6b25lLkNBTkNFTEVEO1xuICAgICAgICB9XG4gICAgICAgIGZpbGUueGhyLmFib3J0KCk7XG4gICAgICAgIGZvciAoX2ogPSAwLCBfbGVuMSA9IGdyb3VwZWRGaWxlcy5sZW5ndGg7IF9qIDwgX2xlbjE7IF9qKyspIHtcbiAgICAgICAgICBncm91cGVkRmlsZSA9IGdyb3VwZWRGaWxlc1tfal07XG4gICAgICAgICAgdGhpcy5lbWl0KFwiY2FuY2VsZWRcIiwgZ3JvdXBlZEZpbGUpO1xuICAgICAgICB9XG4gICAgICAgIGlmICh0aGlzLm9wdGlvbnMudXBsb2FkTXVsdGlwbGUpIHtcbiAgICAgICAgICB0aGlzLmVtaXQoXCJjYW5jZWxlZG11bHRpcGxlXCIsIGdyb3VwZWRGaWxlcyk7XG4gICAgICAgIH1cbiAgICAgIH0gZWxzZSBpZiAoKF9yZWYgPSBmaWxlLnN0YXR1cykgPT09IERyb3B6b25lLkFEREVEIHx8IF9yZWYgPT09IERyb3B6b25lLlFVRVVFRCkge1xuICAgICAgICBmaWxlLnN0YXR1cyA9IERyb3B6b25lLkNBTkNFTEVEO1xuICAgICAgICB0aGlzLmVtaXQoXCJjYW5jZWxlZFwiLCBmaWxlKTtcbiAgICAgICAgaWYgKHRoaXMub3B0aW9ucy51cGxvYWRNdWx0aXBsZSkge1xuICAgICAgICAgIHRoaXMuZW1pdChcImNhbmNlbGVkbXVsdGlwbGVcIiwgW2ZpbGVdKTtcbiAgICAgICAgfVxuICAgICAgfVxuICAgICAgaWYgKHRoaXMub3B0aW9ucy5hdXRvUHJvY2Vzc1F1ZXVlKSB7XG4gICAgICAgIHJldHVybiB0aGlzLnByb2Nlc3NRdWV1ZSgpO1xuICAgICAgfVxuICAgIH07XG5cbiAgICByZXNvbHZlT3B0aW9uID0gZnVuY3Rpb24oKSB7XG4gICAgICB2YXIgYXJncywgb3B0aW9uO1xuICAgICAgb3B0aW9uID0gYXJndW1lbnRzWzBdLCBhcmdzID0gMiA8PSBhcmd1bWVudHMubGVuZ3RoID8gX19zbGljZS5jYWxsKGFyZ3VtZW50cywgMSkgOiBbXTtcbiAgICAgIGlmICh0eXBlb2Ygb3B0aW9uID09PSAnZnVuY3Rpb24nKSB7XG4gICAgICAgIHJldHVybiBvcHRpb24uYXBwbHkodGhpcywgYXJncyk7XG4gICAgICB9XG4gICAgICByZXR1cm4gb3B0aW9uO1xuICAgIH07XG5cbiAgICBEcm9wem9uZS5wcm90b3R5cGUudXBsb2FkRmlsZSA9IGZ1bmN0aW9uKGZpbGUpIHtcbiAgICAgIHJldHVybiB0aGlzLnVwbG9hZEZpbGVzKFtmaWxlXSk7XG4gICAgfTtcblxuICAgIERyb3B6b25lLnByb3RvdHlwZS51cGxvYWRGaWxlcyA9IGZ1bmN0aW9uKGZpbGVzKSB7XG4gICAgICB2YXIgZmlsZSwgZm9ybURhdGEsIGhhbmRsZUVycm9yLCBoZWFkZXJOYW1lLCBoZWFkZXJWYWx1ZSwgaGVhZGVycywgaSwgaW5wdXQsIGlucHV0TmFtZSwgaW5wdXRUeXBlLCBrZXksIG1ldGhvZCwgb3B0aW9uLCBwcm9ncmVzc09iaiwgcmVzcG9uc2UsIHVwZGF0ZVByb2dyZXNzLCB1cmwsIHZhbHVlLCB4aHIsIF9pLCBfaiwgX2ssIF9sLCBfbGVuLCBfbGVuMSwgX2xlbjIsIF9sZW4zLCBfbSwgX3JlZiwgX3JlZjEsIF9yZWYyLCBfcmVmMywgX3JlZjQsIF9yZWY1O1xuICAgICAgeGhyID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XG4gICAgICBmb3IgKF9pID0gMCwgX2xlbiA9IGZpbGVzLmxlbmd0aDsgX2kgPCBfbGVuOyBfaSsrKSB7XG4gICAgICAgIGZpbGUgPSBmaWxlc1tfaV07XG4gICAgICAgIGZpbGUueGhyID0geGhyO1xuICAgICAgfVxuICAgICAgbWV0aG9kID0gcmVzb2x2ZU9wdGlvbih0aGlzLm9wdGlvbnMubWV0aG9kLCBmaWxlcyk7XG4gICAgICB1cmwgPSByZXNvbHZlT3B0aW9uKHRoaXMub3B0aW9ucy51cmwsIGZpbGVzKTtcbiAgICAgIHhoci5vcGVuKG1ldGhvZCwgdXJsLCB0cnVlKTtcbiAgICAgIHhoci53aXRoQ3JlZGVudGlhbHMgPSAhIXRoaXMub3B0aW9ucy53aXRoQ3JlZGVudGlhbHM7XG4gICAgICByZXNwb25zZSA9IG51bGw7XG4gICAgICBoYW5kbGVFcnJvciA9IChmdW5jdGlvbihfdGhpcykge1xuICAgICAgICByZXR1cm4gZnVuY3Rpb24oKSB7XG4gICAgICAgICAgdmFyIF9qLCBfbGVuMSwgX3Jlc3VsdHM7XG4gICAgICAgICAgX3Jlc3VsdHMgPSBbXTtcbiAgICAgICAgICBmb3IgKF9qID0gMCwgX2xlbjEgPSBmaWxlcy5sZW5ndGg7IF9qIDwgX2xlbjE7IF9qKyspIHtcbiAgICAgICAgICAgIGZpbGUgPSBmaWxlc1tfal07XG4gICAgICAgICAgICBfcmVzdWx0cy5wdXNoKF90aGlzLl9lcnJvclByb2Nlc3NpbmcoZmlsZXMsIHJlc3BvbnNlIHx8IF90aGlzLm9wdGlvbnMuZGljdFJlc3BvbnNlRXJyb3IucmVwbGFjZShcInt7c3RhdHVzQ29kZX19XCIsIHhoci5zdGF0dXMpLCB4aHIpKTtcbiAgICAgICAgICB9XG4gICAgICAgICAgcmV0dXJuIF9yZXN1bHRzO1xuICAgICAgICB9O1xuICAgICAgfSkodGhpcyk7XG4gICAgICB1cGRhdGVQcm9ncmVzcyA9IChmdW5jdGlvbihfdGhpcykge1xuICAgICAgICByZXR1cm4gZnVuY3Rpb24oZSkge1xuICAgICAgICAgIHZhciBhbGxGaWxlc0ZpbmlzaGVkLCBwcm9ncmVzcywgX2osIF9rLCBfbCwgX2xlbjEsIF9sZW4yLCBfbGVuMywgX3Jlc3VsdHM7XG4gICAgICAgICAgaWYgKGUgIT0gbnVsbCkge1xuICAgICAgICAgICAgcHJvZ3Jlc3MgPSAxMDAgKiBlLmxvYWRlZCAvIGUudG90YWw7XG4gICAgICAgICAgICBmb3IgKF9qID0gMCwgX2xlbjEgPSBmaWxlcy5sZW5ndGg7IF9qIDwgX2xlbjE7IF9qKyspIHtcbiAgICAgICAgICAgICAgZmlsZSA9IGZpbGVzW19qXTtcbiAgICAgICAgICAgICAgZmlsZS51cGxvYWQgPSB7XG4gICAgICAgICAgICAgICAgcHJvZ3Jlc3M6IHByb2dyZXNzLFxuICAgICAgICAgICAgICAgIHRvdGFsOiBlLnRvdGFsLFxuICAgICAgICAgICAgICAgIGJ5dGVzU2VudDogZS5sb2FkZWRcbiAgICAgICAgICAgICAgfTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgYWxsRmlsZXNGaW5pc2hlZCA9IHRydWU7XG4gICAgICAgICAgICBwcm9ncmVzcyA9IDEwMDtcbiAgICAgICAgICAgIGZvciAoX2sgPSAwLCBfbGVuMiA9IGZpbGVzLmxlbmd0aDsgX2sgPCBfbGVuMjsgX2srKykge1xuICAgICAgICAgICAgICBmaWxlID0gZmlsZXNbX2tdO1xuICAgICAgICAgICAgICBpZiAoIShmaWxlLnVwbG9hZC5wcm9ncmVzcyA9PT0gMTAwICYmIGZpbGUudXBsb2FkLmJ5dGVzU2VudCA9PT0gZmlsZS51cGxvYWQudG90YWwpKSB7XG4gICAgICAgICAgICAgICAgYWxsRmlsZXNGaW5pc2hlZCA9IGZhbHNlO1xuICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgIGZpbGUudXBsb2FkLnByb2dyZXNzID0gcHJvZ3Jlc3M7XG4gICAgICAgICAgICAgIGZpbGUudXBsb2FkLmJ5dGVzU2VudCA9IGZpbGUudXBsb2FkLnRvdGFsO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYgKGFsbEZpbGVzRmluaXNoZWQpIHtcbiAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgfVxuICAgICAgICAgIH1cbiAgICAgICAgICBfcmVzdWx0cyA9IFtdO1xuICAgICAgICAgIGZvciAoX2wgPSAwLCBfbGVuMyA9IGZpbGVzLmxlbmd0aDsgX2wgPCBfbGVuMzsgX2wrKykge1xuICAgICAgICAgICAgZmlsZSA9IGZpbGVzW19sXTtcbiAgICAgICAgICAgIF9yZXN1bHRzLnB1c2goX3RoaXMuZW1pdChcInVwbG9hZHByb2dyZXNzXCIsIGZpbGUsIHByb2dyZXNzLCBmaWxlLnVwbG9hZC5ieXRlc1NlbnQpKTtcbiAgICAgICAgICB9XG4gICAgICAgICAgcmV0dXJuIF9yZXN1bHRzO1xuICAgICAgICB9O1xuICAgICAgfSkodGhpcyk7XG4gICAgICB4aHIub25sb2FkID0gKGZ1bmN0aW9uKF90aGlzKSB7XG4gICAgICAgIHJldHVybiBmdW5jdGlvbihlKSB7XG4gICAgICAgICAgdmFyIF9yZWY7XG4gICAgICAgICAgaWYgKGZpbGVzWzBdLnN0YXR1cyA9PT0gRHJvcHpvbmUuQ0FOQ0VMRUQpIHtcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICB9XG4gICAgICAgICAgaWYgKHhoci5yZWFkeVN0YXRlICE9PSA0KSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgfVxuICAgICAgICAgIHJlc3BvbnNlID0geGhyLnJlc3BvbnNlVGV4dDtcbiAgICAgICAgICBpZiAoeGhyLmdldFJlc3BvbnNlSGVhZGVyKFwiY29udGVudC10eXBlXCIpICYmIH54aHIuZ2V0UmVzcG9uc2VIZWFkZXIoXCJjb250ZW50LXR5cGVcIikuaW5kZXhPZihcImFwcGxpY2F0aW9uL2pzb25cIikpIHtcbiAgICAgICAgICAgIHRyeSB7XG4gICAgICAgICAgICAgIHJlc3BvbnNlID0gSlNPTi5wYXJzZShyZXNwb25zZSk7XG4gICAgICAgICAgICB9IGNhdGNoIChfZXJyb3IpIHtcbiAgICAgICAgICAgICAgZSA9IF9lcnJvcjtcbiAgICAgICAgICAgICAgcmVzcG9uc2UgPSBcIkludmFsaWQgSlNPTiByZXNwb25zZSBmcm9tIHNlcnZlci5cIjtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9XG4gICAgICAgICAgdXBkYXRlUHJvZ3Jlc3MoKTtcbiAgICAgICAgICBpZiAoISgoMjAwIDw9IChfcmVmID0geGhyLnN0YXR1cykgJiYgX3JlZiA8IDMwMCkpKSB7XG4gICAgICAgICAgICByZXR1cm4gaGFuZGxlRXJyb3IoKTtcbiAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgcmV0dXJuIF90aGlzLl9maW5pc2hlZChmaWxlcywgcmVzcG9uc2UsIGUpO1xuICAgICAgICAgIH1cbiAgICAgICAgfTtcbiAgICAgIH0pKHRoaXMpO1xuICAgICAgeGhyLm9uZXJyb3IgPSAoZnVuY3Rpb24oX3RoaXMpIHtcbiAgICAgICAgcmV0dXJuIGZ1bmN0aW9uKCkge1xuICAgICAgICAgIGlmIChmaWxlc1swXS5zdGF0dXMgPT09IERyb3B6b25lLkNBTkNFTEVEKSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgfVxuICAgICAgICAgIHJldHVybiBoYW5kbGVFcnJvcigpO1xuICAgICAgICB9O1xuICAgICAgfSkodGhpcyk7XG4gICAgICBwcm9ncmVzc09iaiA9IChfcmVmID0geGhyLnVwbG9hZCkgIT0gbnVsbCA/IF9yZWYgOiB4aHI7XG4gICAgICBwcm9ncmVzc09iai5vbnByb2dyZXNzID0gdXBkYXRlUHJvZ3Jlc3M7XG4gICAgICBoZWFkZXJzID0ge1xuICAgICAgICBcIkFjY2VwdFwiOiBcImFwcGxpY2F0aW9uL2pzb25cIixcbiAgICAgICAgXCJDYWNoZS1Db250cm9sXCI6IFwibm8tY2FjaGVcIixcbiAgICAgICAgXCJYLVJlcXVlc3RlZC1XaXRoXCI6IFwiWE1MSHR0cFJlcXVlc3RcIlxuICAgICAgfTtcbiAgICAgIGlmICh0aGlzLm9wdGlvbnMuaGVhZGVycykge1xuICAgICAgICBleHRlbmQoaGVhZGVycywgdGhpcy5vcHRpb25zLmhlYWRlcnMpO1xuICAgICAgfVxuICAgICAgZm9yIChoZWFkZXJOYW1lIGluIGhlYWRlcnMpIHtcbiAgICAgICAgaGVhZGVyVmFsdWUgPSBoZWFkZXJzW2hlYWRlck5hbWVdO1xuICAgICAgICBpZiAoaGVhZGVyVmFsdWUpIHtcbiAgICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcihoZWFkZXJOYW1lLCBoZWFkZXJWYWx1ZSk7XG4gICAgICAgIH1cbiAgICAgIH1cbiAgICAgIGZvcm1EYXRhID0gbmV3IEZvcm1EYXRhKCk7XG4gICAgICBpZiAodGhpcy5vcHRpb25zLnBhcmFtcykge1xuICAgICAgICBfcmVmMSA9IHRoaXMub3B0aW9ucy5wYXJhbXM7XG4gICAgICAgIGZvciAoa2V5IGluIF9yZWYxKSB7XG4gICAgICAgICAgdmFsdWUgPSBfcmVmMVtrZXldO1xuICAgICAgICAgIGZvcm1EYXRhLmFwcGVuZChrZXksIHZhbHVlKTtcbiAgICAgICAgfVxuICAgICAgfVxuICAgICAgZm9yIChfaiA9IDAsIF9sZW4xID0gZmlsZXMubGVuZ3RoOyBfaiA8IF9sZW4xOyBfaisrKSB7XG4gICAgICAgIGZpbGUgPSBmaWxlc1tfal07XG4gICAgICAgIHRoaXMuZW1pdChcInNlbmRpbmdcIiwgZmlsZSwgeGhyLCBmb3JtRGF0YSk7XG4gICAgICB9XG4gICAgICBpZiAodGhpcy5vcHRpb25zLnVwbG9hZE11bHRpcGxlKSB7XG4gICAgICAgIHRoaXMuZW1pdChcInNlbmRpbmdtdWx0aXBsZVwiLCBmaWxlcywgeGhyLCBmb3JtRGF0YSk7XG4gICAgICB9XG4gICAgICBpZiAodGhpcy5lbGVtZW50LnRhZ05hbWUgPT09IFwiRk9STVwiKSB7XG4gICAgICAgIF9yZWYyID0gdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoXCJpbnB1dCwgdGV4dGFyZWEsIHNlbGVjdCwgYnV0dG9uXCIpO1xuICAgICAgICBmb3IgKF9rID0gMCwgX2xlbjIgPSBfcmVmMi5sZW5ndGg7IF9rIDwgX2xlbjI7IF9rKyspIHtcbiAgICAgICAgICBpbnB1dCA9IF9yZWYyW19rXTtcbiAgICAgICAgICBpbnB1dE5hbWUgPSBpbnB1dC5nZXRBdHRyaWJ1dGUoXCJuYW1lXCIpO1xuICAgICAgICAgIGlucHV0VHlwZSA9IGlucHV0LmdldEF0dHJpYnV0ZShcInR5cGVcIik7XG4gICAgICAgICAgaWYgKGlucHV0LnRhZ05hbWUgPT09IFwiU0VMRUNUXCIgJiYgaW5wdXQuaGFzQXR0cmlidXRlKFwibXVsdGlwbGVcIikpIHtcbiAgICAgICAgICAgIF9yZWYzID0gaW5wdXQub3B0aW9ucztcbiAgICAgICAgICAgIGZvciAoX2wgPSAwLCBfbGVuMyA9IF9yZWYzLmxlbmd0aDsgX2wgPCBfbGVuMzsgX2wrKykge1xuICAgICAgICAgICAgICBvcHRpb24gPSBfcmVmM1tfbF07XG4gICAgICAgICAgICAgIGlmIChvcHRpb24uc2VsZWN0ZWQpIHtcbiAgICAgICAgICAgICAgICBmb3JtRGF0YS5hcHBlbmQoaW5wdXROYW1lLCBvcHRpb24udmFsdWUpO1xuICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgfSBlbHNlIGlmICghaW5wdXRUeXBlIHx8ICgoX3JlZjQgPSBpbnB1dFR5cGUudG9Mb3dlckNhc2UoKSkgIT09IFwiY2hlY2tib3hcIiAmJiBfcmVmNCAhPT0gXCJyYWRpb1wiKSB8fCBpbnB1dC5jaGVja2VkKSB7XG4gICAgICAgICAgICBmb3JtRGF0YS5hcHBlbmQoaW5wdXROYW1lLCBpbnB1dC52YWx1ZSk7XG4gICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICB9XG4gICAgICBmb3IgKGkgPSBfbSA9IDAsIF9yZWY1ID0gZmlsZXMubGVuZ3RoIC0gMTsgMCA8PSBfcmVmNSA/IF9tIDw9IF9yZWY1IDogX20gPj0gX3JlZjU7IGkgPSAwIDw9IF9yZWY1ID8gKytfbSA6IC0tX20pIHtcbiAgICAgICAgZm9ybURhdGEuYXBwZW5kKHRoaXMuX2dldFBhcmFtTmFtZShpKSwgZmlsZXNbaV0sIHRoaXMuX3JlbmFtZUZpbGVuYW1lKGZpbGVzW2ldLm5hbWUpKTtcbiAgICAgIH1cbiAgICAgIHJldHVybiB0aGlzLnN1Ym1pdFJlcXVlc3QoeGhyLCBmb3JtRGF0YSwgZmlsZXMpO1xuICAgIH07XG5cbiAgICBEcm9wem9uZS5wcm90b3R5cGUuc3VibWl0UmVxdWVzdCA9IGZ1bmN0aW9uKHhociwgZm9ybURhdGEsIGZpbGVzKSB7XG4gICAgICByZXR1cm4geGhyLnNlbmQoZm9ybURhdGEpO1xuICAgIH07XG5cbiAgICBEcm9wem9uZS5wcm90b3R5cGUuX2ZpbmlzaGVkID0gZnVuY3Rpb24oZmlsZXMsIHJlc3BvbnNlVGV4dCwgZSkge1xuICAgICAgdmFyIGZpbGUsIF9pLCBfbGVuO1xuICAgICAgZm9yIChfaSA9IDAsIF9sZW4gPSBmaWxlcy5sZW5ndGg7IF9pIDwgX2xlbjsgX2krKykge1xuICAgICAgICBmaWxlID0gZmlsZXNbX2ldO1xuICAgICAgICBmaWxlLnN0YXR1cyA9IERyb3B6b25lLlNVQ0NFU1M7XG4gICAgICAgIHRoaXMuZW1pdChcInN1Y2Nlc3NcIiwgZmlsZSwgcmVzcG9uc2VUZXh0LCBlKTtcbiAgICAgICAgdGhpcy5lbWl0KFwiY29tcGxldGVcIiwgZmlsZSk7XG4gICAgICB9XG4gICAgICBpZiAodGhpcy5vcHRpb25zLnVwbG9hZE11bHRpcGxlKSB7XG4gICAgICAgIHRoaXMuZW1pdChcInN1Y2Nlc3NtdWx0aXBsZVwiLCBmaWxlcywgcmVzcG9uc2VUZXh0LCBlKTtcbiAgICAgICAgdGhpcy5lbWl0KFwiY29tcGxldGVtdWx0aXBsZVwiLCBmaWxlcyk7XG4gICAgICB9XG4gICAgICBpZiAodGhpcy5vcHRpb25zLmF1dG9Qcm9jZXNzUXVldWUpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMucHJvY2Vzc1F1ZXVlKCk7XG4gICAgICB9XG4gICAgfTtcblxuICAgIERyb3B6b25lLnByb3RvdHlwZS5fZXJyb3JQcm9jZXNzaW5nID0gZnVuY3Rpb24oZmlsZXMsIG1lc3NhZ2UsIHhocikge1xuICAgICAgdmFyIGZpbGUsIF9pLCBfbGVuO1xuICAgICAgZm9yIChfaSA9IDAsIF9sZW4gPSBmaWxlcy5sZW5ndGg7IF9pIDwgX2xlbjsgX2krKykge1xuICAgICAgICBmaWxlID0gZmlsZXNbX2ldO1xuICAgICAgICBmaWxlLnN0YXR1cyA9IERyb3B6b25lLkVSUk9SO1xuICAgICAgICB0aGlzLmVtaXQoXCJlcnJvclwiLCBmaWxlLCBtZXNzYWdlLCB4aHIpO1xuICAgICAgICB0aGlzLmVtaXQoXCJjb21wbGV0ZVwiLCBmaWxlKTtcbiAgICAgIH1cbiAgICAgIGlmICh0aGlzLm9wdGlvbnMudXBsb2FkTXVsdGlwbGUpIHtcbiAgICAgICAgdGhpcy5lbWl0KFwiZXJyb3JtdWx0aXBsZVwiLCBmaWxlcywgbWVzc2FnZSwgeGhyKTtcbiAgICAgICAgdGhpcy5lbWl0KFwiY29tcGxldGVtdWx0aXBsZVwiLCBmaWxlcyk7XG4gICAgICB9XG4gICAgICBpZiAodGhpcy5vcHRpb25zLmF1dG9Qcm9jZXNzUXVldWUpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMucHJvY2Vzc1F1ZXVlKCk7XG4gICAgICB9XG4gICAgfTtcblxuICAgIHJldHVybiBEcm9wem9uZTtcblxuICB9KShFbWl0dGVyKTtcblxuICBEcm9wem9uZS52ZXJzaW9uID0gXCI0LjMuMFwiO1xuXG4gIERyb3B6b25lLm9wdGlvbnMgPSB7fTtcblxuICBEcm9wem9uZS5vcHRpb25zRm9yRWxlbWVudCA9IGZ1bmN0aW9uKGVsZW1lbnQpIHtcbiAgICBpZiAoZWxlbWVudC5nZXRBdHRyaWJ1dGUoXCJpZFwiKSkge1xuICAgICAgcmV0dXJuIERyb3B6b25lLm9wdGlvbnNbY2FtZWxpemUoZWxlbWVudC5nZXRBdHRyaWJ1dGUoXCJpZFwiKSldO1xuICAgIH0gZWxzZSB7XG4gICAgICByZXR1cm4gdm9pZCAwO1xuICAgIH1cbiAgfTtcblxuICBEcm9wem9uZS5pbnN0YW5jZXMgPSBbXTtcblxuICBEcm9wem9uZS5mb3JFbGVtZW50ID0gZnVuY3Rpb24oZWxlbWVudCkge1xuICAgIGlmICh0eXBlb2YgZWxlbWVudCA9PT0gXCJzdHJpbmdcIikge1xuICAgICAgZWxlbWVudCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoZWxlbWVudCk7XG4gICAgfVxuICAgIGlmICgoZWxlbWVudCAhPSBudWxsID8gZWxlbWVudC5kcm9wem9uZSA6IHZvaWQgMCkgPT0gbnVsbCkge1xuICAgICAgdGhyb3cgbmV3IEVycm9yKFwiTm8gRHJvcHpvbmUgZm91bmQgZm9yIGdpdmVuIGVsZW1lbnQuIFRoaXMgaXMgcHJvYmFibHkgYmVjYXVzZSB5b3UncmUgdHJ5aW5nIHRvIGFjY2VzcyBpdCBiZWZvcmUgRHJvcHpvbmUgaGFkIHRoZSB0aW1lIHRvIGluaXRpYWxpemUuIFVzZSB0aGUgYGluaXRgIG9wdGlvbiB0byBzZXR1cCBhbnkgYWRkaXRpb25hbCBvYnNlcnZlcnMgb24geW91ciBEcm9wem9uZS5cIik7XG4gICAgfVxuICAgIHJldHVybiBlbGVtZW50LmRyb3B6b25lO1xuICB9O1xuXG4gIERyb3B6b25lLmF1dG9EaXNjb3ZlciA9IHRydWU7XG5cbiAgRHJvcHpvbmUuZGlzY292ZXIgPSBmdW5jdGlvbigpIHtcbiAgICB2YXIgY2hlY2tFbGVtZW50cywgZHJvcHpvbmUsIGRyb3B6b25lcywgX2ksIF9sZW4sIF9yZXN1bHRzO1xuICAgIGlmIChkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKSB7XG4gICAgICBkcm9wem9uZXMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKFwiLmRyb3B6b25lXCIpO1xuICAgIH0gZWxzZSB7XG4gICAgICBkcm9wem9uZXMgPSBbXTtcbiAgICAgIGNoZWNrRWxlbWVudHMgPSBmdW5jdGlvbihlbGVtZW50cykge1xuICAgICAgICB2YXIgZWwsIF9pLCBfbGVuLCBfcmVzdWx0cztcbiAgICAgICAgX3Jlc3VsdHMgPSBbXTtcbiAgICAgICAgZm9yIChfaSA9IDAsIF9sZW4gPSBlbGVtZW50cy5sZW5ndGg7IF9pIDwgX2xlbjsgX2krKykge1xuICAgICAgICAgIGVsID0gZWxlbWVudHNbX2ldO1xuICAgICAgICAgIGlmICgvKF58IClkcm9wem9uZSgkfCApLy50ZXN0KGVsLmNsYXNzTmFtZSkpIHtcbiAgICAgICAgICAgIF9yZXN1bHRzLnB1c2goZHJvcHpvbmVzLnB1c2goZWwpKTtcbiAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgX3Jlc3VsdHMucHVzaCh2b2lkIDApO1xuICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gX3Jlc3VsdHM7XG4gICAgICB9O1xuICAgICAgY2hlY2tFbGVtZW50cyhkb2N1bWVudC5nZXRFbGVtZW50c0J5VGFnTmFtZShcImRpdlwiKSk7XG4gICAgICBjaGVja0VsZW1lbnRzKGRvY3VtZW50LmdldEVsZW1lbnRzQnlUYWdOYW1lKFwiZm9ybVwiKSk7XG4gICAgfVxuICAgIF9yZXN1bHRzID0gW107XG4gICAgZm9yIChfaSA9IDAsIF9sZW4gPSBkcm9wem9uZXMubGVuZ3RoOyBfaSA8IF9sZW47IF9pKyspIHtcbiAgICAgIGRyb3B6b25lID0gZHJvcHpvbmVzW19pXTtcbiAgICAgIGlmIChEcm9wem9uZS5vcHRpb25zRm9yRWxlbWVudChkcm9wem9uZSkgIT09IGZhbHNlKSB7XG4gICAgICAgIF9yZXN1bHRzLnB1c2gobmV3IERyb3B6b25lKGRyb3B6b25lKSk7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBfcmVzdWx0cy5wdXNoKHZvaWQgMCk7XG4gICAgICB9XG4gICAgfVxuICAgIHJldHVybiBfcmVzdWx0cztcbiAgfTtcblxuICBEcm9wem9uZS5ibGFja2xpc3RlZEJyb3dzZXJzID0gWy9vcGVyYS4qTWFjaW50b3NoLip2ZXJzaW9uXFwvMTIvaV07XG5cbiAgRHJvcHpvbmUuaXNCcm93c2VyU3VwcG9ydGVkID0gZnVuY3Rpb24oKSB7XG4gICAgdmFyIGNhcGFibGVCcm93c2VyLCByZWdleCwgX2ksIF9sZW4sIF9yZWY7XG4gICAgY2FwYWJsZUJyb3dzZXIgPSB0cnVlO1xuICAgIGlmICh3aW5kb3cuRmlsZSAmJiB3aW5kb3cuRmlsZVJlYWRlciAmJiB3aW5kb3cuRmlsZUxpc3QgJiYgd2luZG93LkJsb2IgJiYgd2luZG93LkZvcm1EYXRhICYmIGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IpIHtcbiAgICAgIGlmICghKFwiY2xhc3NMaXN0XCIgaW4gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImFcIikpKSB7XG4gICAgICAgIGNhcGFibGVCcm93c2VyID0gZmFsc2U7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBfcmVmID0gRHJvcHpvbmUuYmxhY2tsaXN0ZWRCcm93c2VycztcbiAgICAgICAgZm9yIChfaSA9IDAsIF9sZW4gPSBfcmVmLmxlbmd0aDsgX2kgPCBfbGVuOyBfaSsrKSB7XG4gICAgICAgICAgcmVnZXggPSBfcmVmW19pXTtcbiAgICAgICAgICBpZiAocmVnZXgudGVzdChuYXZpZ2F0b3IudXNlckFnZW50KSkge1xuICAgICAgICAgICAgY2FwYWJsZUJyb3dzZXIgPSBmYWxzZTtcbiAgICAgICAgICAgIGNvbnRpbnVlO1xuICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgfVxuICAgIH0gZWxzZSB7XG4gICAgICBjYXBhYmxlQnJvd3NlciA9IGZhbHNlO1xuICAgIH1cbiAgICByZXR1cm4gY2FwYWJsZUJyb3dzZXI7XG4gIH07XG5cbiAgd2l0aG91dCA9IGZ1bmN0aW9uKGxpc3QsIHJlamVjdGVkSXRlbSkge1xuICAgIHZhciBpdGVtLCBfaSwgX2xlbiwgX3Jlc3VsdHM7XG4gICAgX3Jlc3VsdHMgPSBbXTtcbiAgICBmb3IgKF9pID0gMCwgX2xlbiA9IGxpc3QubGVuZ3RoOyBfaSA8IF9sZW47IF9pKyspIHtcbiAgICAgIGl0ZW0gPSBsaXN0W19pXTtcbiAgICAgIGlmIChpdGVtICE9PSByZWplY3RlZEl0ZW0pIHtcbiAgICAgICAgX3Jlc3VsdHMucHVzaChpdGVtKTtcbiAgICAgIH1cbiAgICB9XG4gICAgcmV0dXJuIF9yZXN1bHRzO1xuICB9O1xuXG4gIGNhbWVsaXplID0gZnVuY3Rpb24oc3RyKSB7XG4gICAgcmV0dXJuIHN0ci5yZXBsYWNlKC9bXFwtX10oXFx3KS9nLCBmdW5jdGlvbihtYXRjaCkge1xuICAgICAgcmV0dXJuIG1hdGNoLmNoYXJBdCgxKS50b1VwcGVyQ2FzZSgpO1xuICAgIH0pO1xuICB9O1xuXG4gIERyb3B6b25lLmNyZWF0ZUVsZW1lbnQgPSBmdW5jdGlvbihzdHJpbmcpIHtcbiAgICB2YXIgZGl2O1xuICAgIGRpdiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJkaXZcIik7XG4gICAgZGl2LmlubmVySFRNTCA9IHN0cmluZztcbiAgICByZXR1cm4gZGl2LmNoaWxkTm9kZXNbMF07XG4gIH07XG5cbiAgRHJvcHpvbmUuZWxlbWVudEluc2lkZSA9IGZ1bmN0aW9uKGVsZW1lbnQsIGNvbnRhaW5lcikge1xuICAgIGlmIChlbGVtZW50ID09PSBjb250YWluZXIpIHtcbiAgICAgIHJldHVybiB0cnVlO1xuICAgIH1cbiAgICB3aGlsZSAoZWxlbWVudCA9IGVsZW1lbnQucGFyZW50Tm9kZSkge1xuICAgICAgaWYgKGVsZW1lbnQgPT09IGNvbnRhaW5lcikge1xuICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgIH1cbiAgICB9XG4gICAgcmV0dXJuIGZhbHNlO1xuICB9O1xuXG4gIERyb3B6b25lLmdldEVsZW1lbnQgPSBmdW5jdGlvbihlbCwgbmFtZSkge1xuICAgIHZhciBlbGVtZW50O1xuICAgIGlmICh0eXBlb2YgZWwgPT09IFwic3RyaW5nXCIpIHtcbiAgICAgIGVsZW1lbnQgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKGVsKTtcbiAgICB9IGVsc2UgaWYgKGVsLm5vZGVUeXBlICE9IG51bGwpIHtcbiAgICAgIGVsZW1lbnQgPSBlbDtcbiAgICB9XG4gICAgaWYgKGVsZW1lbnQgPT0gbnVsbCkge1xuICAgICAgdGhyb3cgbmV3IEVycm9yKFwiSW52YWxpZCBgXCIgKyBuYW1lICsgXCJgIG9wdGlvbiBwcm92aWRlZC4gUGxlYXNlIHByb3ZpZGUgYSBDU1Mgc2VsZWN0b3Igb3IgYSBwbGFpbiBIVE1MIGVsZW1lbnQuXCIpO1xuICAgIH1cbiAgICByZXR1cm4gZWxlbWVudDtcbiAgfTtcblxuICBEcm9wem9uZS5nZXRFbGVtZW50cyA9IGZ1bmN0aW9uKGVscywgbmFtZSkge1xuICAgIHZhciBlLCBlbCwgZWxlbWVudHMsIF9pLCBfaiwgX2xlbiwgX2xlbjEsIF9yZWY7XG4gICAgaWYgKGVscyBpbnN0YW5jZW9mIEFycmF5KSB7XG4gICAgICBlbGVtZW50cyA9IFtdO1xuICAgICAgdHJ5IHtcbiAgICAgICAgZm9yIChfaSA9IDAsIF9sZW4gPSBlbHMubGVuZ3RoOyBfaSA8IF9sZW47IF9pKyspIHtcbiAgICAgICAgICBlbCA9IGVsc1tfaV07XG4gICAgICAgICAgZWxlbWVudHMucHVzaCh0aGlzLmdldEVsZW1lbnQoZWwsIG5hbWUpKTtcbiAgICAgICAgfVxuICAgICAgfSBjYXRjaCAoX2Vycm9yKSB7XG4gICAgICAgIGUgPSBfZXJyb3I7XG4gICAgICAgIGVsZW1lbnRzID0gbnVsbDtcbiAgICAgIH1cbiAgICB9IGVsc2UgaWYgKHR5cGVvZiBlbHMgPT09IFwic3RyaW5nXCIpIHtcbiAgICAgIGVsZW1lbnRzID0gW107XG4gICAgICBfcmVmID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbChlbHMpO1xuICAgICAgZm9yIChfaiA9IDAsIF9sZW4xID0gX3JlZi5sZW5ndGg7IF9qIDwgX2xlbjE7IF9qKyspIHtcbiAgICAgICAgZWwgPSBfcmVmW19qXTtcbiAgICAgICAgZWxlbWVudHMucHVzaChlbCk7XG4gICAgICB9XG4gICAgfSBlbHNlIGlmIChlbHMubm9kZVR5cGUgIT0gbnVsbCkge1xuICAgICAgZWxlbWVudHMgPSBbZWxzXTtcbiAgICB9XG4gICAgaWYgKCEoKGVsZW1lbnRzICE9IG51bGwpICYmIGVsZW1lbnRzLmxlbmd0aCkpIHtcbiAgICAgIHRocm93IG5ldyBFcnJvcihcIkludmFsaWQgYFwiICsgbmFtZSArIFwiYCBvcHRpb24gcHJvdmlkZWQuIFBsZWFzZSBwcm92aWRlIGEgQ1NTIHNlbGVjdG9yLCBhIHBsYWluIEhUTUwgZWxlbWVudCBvciBhIGxpc3Qgb2YgdGhvc2UuXCIpO1xuICAgIH1cbiAgICByZXR1cm4gZWxlbWVudHM7XG4gIH07XG5cbiAgRHJvcHpvbmUuY29uZmlybSA9IGZ1bmN0aW9uKHF1ZXN0aW9uLCBhY2NlcHRlZCwgcmVqZWN0ZWQpIHtcbiAgICBpZiAod2luZG93LmNvbmZpcm0ocXVlc3Rpb24pKSB7XG4gICAgICByZXR1cm4gYWNjZXB0ZWQoKTtcbiAgICB9IGVsc2UgaWYgKHJlamVjdGVkICE9IG51bGwpIHtcbiAgICAgIHJldHVybiByZWplY3RlZCgpO1xuICAgIH1cbiAgfTtcblxuICBEcm9wem9uZS5pc1ZhbGlkRmlsZSA9IGZ1bmN0aW9uKGZpbGUsIGFjY2VwdGVkRmlsZXMpIHtcbiAgICB2YXIgYmFzZU1pbWVUeXBlLCBtaW1lVHlwZSwgdmFsaWRUeXBlLCBfaSwgX2xlbjtcbiAgICBpZiAoIWFjY2VwdGVkRmlsZXMpIHtcbiAgICAgIHJldHVybiB0cnVlO1xuICAgIH1cbiAgICBhY2NlcHRlZEZpbGVzID0gYWNjZXB0ZWRGaWxlcy5zcGxpdChcIixcIik7XG4gICAgbWltZVR5cGUgPSBmaWxlLnR5cGU7XG4gICAgYmFzZU1pbWVUeXBlID0gbWltZVR5cGUucmVwbGFjZSgvXFwvLiokLywgXCJcIik7XG4gICAgZm9yIChfaSA9IDAsIF9sZW4gPSBhY2NlcHRlZEZpbGVzLmxlbmd0aDsgX2kgPCBfbGVuOyBfaSsrKSB7XG4gICAgICB2YWxpZFR5cGUgPSBhY2NlcHRlZEZpbGVzW19pXTtcbiAgICAgIHZhbGlkVHlwZSA9IHZhbGlkVHlwZS50cmltKCk7XG4gICAgICBpZiAodmFsaWRUeXBlLmNoYXJBdCgwKSA9PT0gXCIuXCIpIHtcbiAgICAgICAgaWYgKGZpbGUubmFtZS50b0xvd2VyQ2FzZSgpLmluZGV4T2YodmFsaWRUeXBlLnRvTG93ZXJDYXNlKCksIGZpbGUubmFtZS5sZW5ndGggLSB2YWxpZFR5cGUubGVuZ3RoKSAhPT0gLTEpIHtcbiAgICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICAgICAgfVxuICAgICAgfSBlbHNlIGlmICgvXFwvXFwqJC8udGVzdCh2YWxpZFR5cGUpKSB7XG4gICAgICAgIGlmIChiYXNlTWltZVR5cGUgPT09IHZhbGlkVHlwZS5yZXBsYWNlKC9cXC8uKiQvLCBcIlwiKSkge1xuICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICB9XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBpZiAobWltZVR5cGUgPT09IHZhbGlkVHlwZSkge1xuICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICB9XG4gICAgICB9XG4gICAgfVxuICAgIHJldHVybiBmYWxzZTtcbiAgfTtcblxuICBpZiAodHlwZW9mIGpRdWVyeSAhPT0gXCJ1bmRlZmluZWRcIiAmJiBqUXVlcnkgIT09IG51bGwpIHtcbiAgICBqUXVlcnkuZm4uZHJvcHpvbmUgPSBmdW5jdGlvbihvcHRpb25zKSB7XG4gICAgICByZXR1cm4gdGhpcy5lYWNoKGZ1bmN0aW9uKCkge1xuICAgICAgICByZXR1cm4gbmV3IERyb3B6b25lKHRoaXMsIG9wdGlvbnMpO1xuICAgICAgfSk7XG4gICAgfTtcbiAgfVxuXG4gIGlmICh0eXBlb2YgbW9kdWxlICE9PSBcInVuZGVmaW5lZFwiICYmIG1vZHVsZSAhPT0gbnVsbCkge1xuICAgIG1vZHVsZS5leHBvcnRzID0gRHJvcHpvbmU7XG4gIH0gZWxzZSB7XG4gICAgd2luZG93LkRyb3B6b25lID0gRHJvcHpvbmU7XG4gIH1cblxuICBEcm9wem9uZS5BRERFRCA9IFwiYWRkZWRcIjtcblxuICBEcm9wem9uZS5RVUVVRUQgPSBcInF1ZXVlZFwiO1xuXG4gIERyb3B6b25lLkFDQ0VQVEVEID0gRHJvcHpvbmUuUVVFVUVEO1xuXG4gIERyb3B6b25lLlVQTE9BRElORyA9IFwidXBsb2FkaW5nXCI7XG5cbiAgRHJvcHpvbmUuUFJPQ0VTU0lORyA9IERyb3B6b25lLlVQTE9BRElORztcblxuICBEcm9wem9uZS5DQU5DRUxFRCA9IFwiY2FuY2VsZWRcIjtcblxuICBEcm9wem9uZS5FUlJPUiA9IFwiZXJyb3JcIjtcblxuICBEcm9wem9uZS5TVUNDRVNTID0gXCJzdWNjZXNzXCI7XG5cblxuICAvKlxuICBcbiAgQnVnZml4IGZvciBpT1MgNiBhbmQgN1xuICBTb3VyY2U6IGh0dHA6Ly9zdGFja292ZXJmbG93LmNvbS9xdWVzdGlvbnMvMTE5MjkwOTkvaHRtbDUtY2FudmFzLWRyYXdpbWFnZS1yYXRpby1idWctaW9zXG4gIGJhc2VkIG9uIHRoZSB3b3JrIG9mIGh0dHBzOi8vZ2l0aHViLmNvbS9zdG9taXRhL2lvcy1pbWFnZWZpbGUtbWVnYXBpeGVsXG4gICAqL1xuXG4gIGRldGVjdFZlcnRpY2FsU3F1YXNoID0gZnVuY3Rpb24oaW1nKSB7XG4gICAgdmFyIGFscGhhLCBjYW52YXMsIGN0eCwgZGF0YSwgZXksIGloLCBpdywgcHksIHJhdGlvLCBzeTtcbiAgICBpdyA9IGltZy5uYXR1cmFsV2lkdGg7XG4gICAgaWggPSBpbWcubmF0dXJhbEhlaWdodDtcbiAgICBjYW52YXMgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiY2FudmFzXCIpO1xuICAgIGNhbnZhcy53aWR0aCA9IDE7XG4gICAgY2FudmFzLmhlaWdodCA9IGloO1xuICAgIGN0eCA9IGNhbnZhcy5nZXRDb250ZXh0KFwiMmRcIik7XG4gICAgY3R4LmRyYXdJbWFnZShpbWcsIDAsIDApO1xuICAgIGRhdGEgPSBjdHguZ2V0SW1hZ2VEYXRhKDAsIDAsIDEsIGloKS5kYXRhO1xuICAgIHN5ID0gMDtcbiAgICBleSA9IGloO1xuICAgIHB5ID0gaWg7XG4gICAgd2hpbGUgKHB5ID4gc3kpIHtcbiAgICAgIGFscGhhID0gZGF0YVsocHkgLSAxKSAqIDQgKyAzXTtcbiAgICAgIGlmIChhbHBoYSA9PT0gMCkge1xuICAgICAgICBleSA9IHB5O1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgc3kgPSBweTtcbiAgICAgIH1cbiAgICAgIHB5ID0gKGV5ICsgc3kpID4+IDE7XG4gICAgfVxuICAgIHJhdGlvID0gcHkgLyBpaDtcbiAgICBpZiAocmF0aW8gPT09IDApIHtcbiAgICAgIHJldHVybiAxO1xuICAgIH0gZWxzZSB7XG4gICAgICByZXR1cm4gcmF0aW87XG4gICAgfVxuICB9O1xuXG4gIGRyYXdJbWFnZUlPU0ZpeCA9IGZ1bmN0aW9uKGN0eCwgaW1nLCBzeCwgc3ksIHN3LCBzaCwgZHgsIGR5LCBkdywgZGgpIHtcbiAgICB2YXIgdmVydFNxdWFzaFJhdGlvO1xuICAgIHZlcnRTcXVhc2hSYXRpbyA9IGRldGVjdFZlcnRpY2FsU3F1YXNoKGltZyk7XG4gICAgcmV0dXJuIGN0eC5kcmF3SW1hZ2UoaW1nLCBzeCwgc3ksIHN3LCBzaCwgZHgsIGR5LCBkdywgZGggLyB2ZXJ0U3F1YXNoUmF0aW8pO1xuICB9O1xuXG5cbiAgLypcbiAgICogY29udGVudGxvYWRlZC5qc1xuICAgKlxuICAgKiBBdXRob3I6IERpZWdvIFBlcmluaSAoZGllZ28ucGVyaW5pIGF0IGdtYWlsLmNvbSlcbiAgICogU3VtbWFyeTogY3Jvc3MtYnJvd3NlciB3cmFwcGVyIGZvciBET01Db250ZW50TG9hZGVkXG4gICAqIFVwZGF0ZWQ6IDIwMTAxMDIwXG4gICAqIExpY2Vuc2U6IE1JVFxuICAgKiBWZXJzaW9uOiAxLjJcbiAgICpcbiAgICogVVJMOlxuICAgKiBodHRwOi8vamF2YXNjcmlwdC5ud2JveC5jb20vQ29udGVudExvYWRlZC9cbiAgICogaHR0cDovL2phdmFzY3JpcHQubndib3guY29tL0NvbnRlbnRMb2FkZWQvTUlULUxJQ0VOU0VcbiAgICovXG5cbiAgY29udGVudExvYWRlZCA9IGZ1bmN0aW9uKHdpbiwgZm4pIHtcbiAgICB2YXIgYWRkLCBkb2MsIGRvbmUsIGluaXQsIHBvbGwsIHByZSwgcmVtLCByb290LCB0b3A7XG4gICAgZG9uZSA9IGZhbHNlO1xuICAgIHRvcCA9IHRydWU7XG4gICAgZG9jID0gd2luLmRvY3VtZW50O1xuICAgIHJvb3QgPSBkb2MuZG9jdW1lbnRFbGVtZW50O1xuICAgIGFkZCA9IChkb2MuYWRkRXZlbnRMaXN0ZW5lciA/IFwiYWRkRXZlbnRMaXN0ZW5lclwiIDogXCJhdHRhY2hFdmVudFwiKTtcbiAgICByZW0gPSAoZG9jLmFkZEV2ZW50TGlzdGVuZXIgPyBcInJlbW92ZUV2ZW50TGlzdGVuZXJcIiA6IFwiZGV0YWNoRXZlbnRcIik7XG4gICAgcHJlID0gKGRvYy5hZGRFdmVudExpc3RlbmVyID8gXCJcIiA6IFwib25cIik7XG4gICAgaW5pdCA9IGZ1bmN0aW9uKGUpIHtcbiAgICAgIGlmIChlLnR5cGUgPT09IFwicmVhZHlzdGF0ZWNoYW5nZVwiICYmIGRvYy5yZWFkeVN0YXRlICE9PSBcImNvbXBsZXRlXCIpIHtcbiAgICAgICAgcmV0dXJuO1xuICAgICAgfVxuICAgICAgKGUudHlwZSA9PT0gXCJsb2FkXCIgPyB3aW4gOiBkb2MpW3JlbV0ocHJlICsgZS50eXBlLCBpbml0LCBmYWxzZSk7XG4gICAgICBpZiAoIWRvbmUgJiYgKGRvbmUgPSB0cnVlKSkge1xuICAgICAgICByZXR1cm4gZm4uY2FsbCh3aW4sIGUudHlwZSB8fCBlKTtcbiAgICAgIH1cbiAgICB9O1xuICAgIHBvbGwgPSBmdW5jdGlvbigpIHtcbiAgICAgIHZhciBlO1xuICAgICAgdHJ5IHtcbiAgICAgICAgcm9vdC5kb1Njcm9sbChcImxlZnRcIik7XG4gICAgICB9IGNhdGNoIChfZXJyb3IpIHtcbiAgICAgICAgZSA9IF9lcnJvcjtcbiAgICAgICAgc2V0VGltZW91dChwb2xsLCA1MCk7XG4gICAgICAgIHJldHVybjtcbiAgICAgIH1cbiAgICAgIHJldHVybiBpbml0KFwicG9sbFwiKTtcbiAgICB9O1xuICAgIGlmIChkb2MucmVhZHlTdGF0ZSAhPT0gXCJjb21wbGV0ZVwiKSB7XG4gICAgICBpZiAoZG9jLmNyZWF0ZUV2ZW50T2JqZWN0ICYmIHJvb3QuZG9TY3JvbGwpIHtcbiAgICAgICAgdHJ5IHtcbiAgICAgICAgICB0b3AgPSAhd2luLmZyYW1lRWxlbWVudDtcbiAgICAgICAgfSBjYXRjaCAoX2Vycm9yKSB7fVxuICAgICAgICBpZiAodG9wKSB7XG4gICAgICAgICAgcG9sbCgpO1xuICAgICAgICB9XG4gICAgICB9XG4gICAgICBkb2NbYWRkXShwcmUgKyBcIkRPTUNvbnRlbnRMb2FkZWRcIiwgaW5pdCwgZmFsc2UpO1xuICAgICAgZG9jW2FkZF0ocHJlICsgXCJyZWFkeXN0YXRlY2hhbmdlXCIsIGluaXQsIGZhbHNlKTtcbiAgICAgIHJldHVybiB3aW5bYWRkXShwcmUgKyBcImxvYWRcIiwgaW5pdCwgZmFsc2UpO1xuICAgIH1cbiAgfTtcblxuICBEcm9wem9uZS5fYXV0b0Rpc2NvdmVyRnVuY3Rpb24gPSBmdW5jdGlvbigpIHtcbiAgICBpZiAoRHJvcHpvbmUuYXV0b0Rpc2NvdmVyKSB7XG4gICAgICByZXR1cm4gRHJvcHpvbmUuZGlzY292ZXIoKTtcbiAgICB9XG4gIH07XG5cbiAgY29udGVudExvYWRlZCh3aW5kb3csIERyb3B6b25lLl9hdXRvRGlzY292ZXJGdW5jdGlvbik7XG5cbn0pLmNhbGwodGhpcyk7XG5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL25vZGVfbW9kdWxlcy9kcm9wem9uZS9kaXN0L2Ryb3B6b25lLmpzXG4vLyBtb2R1bGUgaWQgPSAzNFxuLy8gbW9kdWxlIGNodW5rcyA9IDIiXSwic291cmNlUm9vdCI6IiJ9