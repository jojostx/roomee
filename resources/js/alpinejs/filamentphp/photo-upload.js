export default (Alpine) => {
    Alpine.data(
        "customPhotoUploadFormComponent",
        ({
            acceptedFileTypes = ["image/jpg", "image/png", "image/jpeg"],
            isAvatar = false,
            imageCropAspectRatio = 1,
            maxSize = 5242,
            minSize = 10,
            minCroppedWidth = 320,
            maxCroppedWidth = 960,
            defaultImageUrl,
            deleteUploadedFileUsing,
            getUploadedFileUrlsUsing,
            uploadUsing,
            state,
            statePath,
        }) => {
            return {
                state,

                cropCanvasId: `cropCanvas_${statePath}`,

                posterId: `poster_${statePath}`,

                isAvatar,

                hasImage: defaultImageUrl ? true : false,

                croppable: false,

                cropper: null,

                showCropper: false,

                currentInputImage: null,

                fileKeyIndex: {},

                uploadedFileUrlIndex: {},

                shouldUpdateState: true,

                isUploading: false,

                minCroppedHeight: minCroppedWidth / imageCropAspectRatio,

                maxCroppedHeight: maxCroppedWidth / imageCropAspectRatio,

                async init() {
                    this.$watch("state", async () => {
                        if (!this.shouldUpdateState) {
                            return;
                        }

                        document.createElement("p").hasAttribute("multiple");

                        // We don't want to overwrite the files that are already in the input, if they haven't been saved yet.
                        if (
                            Object.values(this.state).filter((file) =>
                                file.startsWith("livewire-file:")
                            ).length
                        ) {
                            return;
                        }
                    });
                },

                initCropper(id, fileUrl) {
                    let elem =
                        this.$refs[this.cropCanvasId] ??
                        document.getElementById(this.cropCanvasId);

                    elem.src = elem ? fileUrl : "";

                    if (!this.isValidHTMLImageElement(elem)) {
                        return;
                    }

                    this.cropper = new Cropper(elem, {
                        aspectRatio: imageCropAspectRatio,
                        viewMode: 1,
                        dragMode: "move",
                        rotatable: false,
                        restore: true,
                        responsive: true,
                        guides: false,
                        center: false,
                        highlight: false,
                        cropBoxMovable: false,
                        cropBoxResizable: false,
                        toggleDragModeOnDblclick: false,

                        zoom: function (event) {
                            if (event.detail.ratio >= 1.25 || this.cropper.getImageData().naturalWidth < minCroppedWidth) {
                                event.preventDefault(); // Prevent zoom in
                            }
                        },
                    });

                    this.croppable = true;

                    this.$dispatch("open-modal", { id });
                },

                async handleFileInputChange(id) {
                    let files = this.$refs.input.files;
                    let file;

                    try {
                        if (files && files.length > 0) {
                            file = files[0];

                            if (!this.validFileType(file.type)) {
                                this.$dispatch("open-alert", {
                                    alert_type: "danger",
                                    message: `Invalid image type. Accepted types: (${acceptedFileTypes
                                        .map((val) => {
                                            val.split("/").pop();
                                        })
                                        .join(", ")})`,
                                    closeAfterTimeout: true,
                                });

                                return;
                            }

                            const isValidDimensions =
                                await this.validFileDimensions(file);

                            if (!isValidDimensions) {
                                this.$dispatch("open-alert", {
                                    alert_type: "danger",
                                    message: `The Image dimensions are not valid`,
                                    closeAfterTimeout: true,
                                });

                                return;
                            }

                            if (!this.validFileSize(file.size)) {
                                this.$dispatch("open-alert", {
                                    alert_type: "danger",
                                    message: `Only images between ${minSize}KB & ${(
                                        maxSize / 1024
                                    ).toFixed(1)}MB are allowed`,
                                    closeAfterTimeout: true,
                                });

                                return;
                            }

                            this.currentInputImage = file;

                            if (URL) {
                                this.initCropper(id, URL.createObjectURL(file));
                            } else if (FileReader) {
                                let reader = new FileReader();

                                reader.onloadend = () => {
                                    this.initCropper(id, reader.result);
                                };

                                reader.readAsDataURL(file);
                            }
                        }
                    } catch (error) {
                        this.$dispatch("open-alert", {
                            alert_type: "danger",
                            message: `Error in loading file`,
                            closeAfterTimeout: true,
                        });
                    }
                },

                updatePreview(imageSrc) {
                    if (typeof imageSrc !== String && !imageSrc.trim()) {
                        return;
                    }

                    this.$refs[this.posterId].src = imageSrc;
                },

                resetCropper() {
                    if (this.cropper) {
                        this.cropper.destroy();
                        this.cropper = null;
                    }

                    this.hasImage = false;
                    this.croppable = false;
                },

                cropAndSave() {
                    if (this.cropper) {
                        let canvas = this.cropper.getCroppedCanvas({
                            minWidth: minCroppedWidth,
                            maxWidth: maxCroppedWidth,
                            minHeight: this.minCroppedHeight,
                            maxHeight: this.maxCroppedHeight,
                        });

                        // convert canvas output to blob and upload to Livewire component
                        canvas.toBlob((blob) => {
                            this.upload(
                                blob,
                                (fileKey) => {
                                    //if image upload is successful set the preview
                                    this.remove(this.uploadedFilekey);

                                    this.uploadedFilekey = fileKey;

                                    this.isUploading = false;

                                    this.updatePreview(
                                        canvas.toDataURL(
                                            this.currentInputImage?.type
                                        )
                                    );

                                    this.resetCropper();

                                    this.$dispatch("open-alert", {
                                        alert_type: "success",
                                        message: `Successfully uploaded file`,
                                        closeAfterTimeout: true,
                                    });
                                },
                                () => {
                                    this.$dispatch("open-alert", {
                                        alert_type: "danger",
                                        message: `Unable to upload file`,
                                        closeAfterTimeout: false,
                                    });
                                },
                                (event) => {
                                    this.isUploading = true;
                                }
                            );
                        }, this.currentInputImage?.type, 1);
                    }
                },

                upload(file, load, error, progress) {
                    this.shouldUpdateState = false;

                    let fileKey = ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(
                        /[018]/g,
                        (c) =>
                            (
                                c ^
                                (crypto.getRandomValues(new Uint8Array(1))[0] &
                                    (15 >> (c / 4)))
                            ).toString(16)
                    );

                    uploadUsing(
                        fileKey,
                        file,
                        (fileKey) => {
                            this.shouldUpdateState = true;

                            load(fileKey);
                        },
                        error,
                        progress
                    );
                },

                // WIP
                remove: async function (source) {
                    let fileKey = this.uploadedFileUrlIndex[source] ?? null;

                    if (!fileKey) {
                        return;
                    }

                    await deleteUploadedFileUsing(fileKey);

                    load();
                },

                getUploadedFileUrls: async function () {
                    const uploadedFileUrls = await getUploadedFileUrlsUsing();

                    this.fileKeyIndex = uploadedFileUrls ?? {};

                    this.uploadedFileUrlIndex = Object.entries(
                        this.fileKeyIndex
                    )
                        .filter((value) => value)
                        .reduce((obj, [key, value]) => {
                            obj[value] = key;

                            return obj;
                        }, {});
                },

                getFiles: async function () {
                    await this.getUploadedFileUrls();

                    let files = [];

                    for (const uploadedFileUrl of Object.values(
                        this.fileKeyIndex
                    )) {
                        if (!uploadedFileUrl) {
                            continue;
                        }

                        files.push({
                            source: uploadedFileUrl,
                            options: {
                                type: "local",
                            },
                        });
                    }

                    return shouldAppendFiles ? files : files.reverse();
                },

                isValidHTMLImageElement(elem) {
                    if (elem?.src) {
                        return true;
                    }

                    return false;
                },

                validFileType(fileType) {
                    return acceptedFileTypes.includes(fileType);
                },

                validFileSize(file_Size) {
                    if (Number.isNaN(fileSize) || fileSize === 0) {
                        return false;
                    }

                    // fileSize in bytes, minSize & maxSize in kilobytes
                    let fileSize = file_Size / 1024;

                    return fileSize < maxSize && fileSize > minSize;
                },

                validFileDimensions(file) {
                    return new Promise((resolve, reject) => {
                        let reader = new FileReader();

                        reader.onloadend = () => {
                            let img = new Image();
                            img.src = reader.result;

                            img.onload = () => {
                                let result = img.width > minCroppedWidth;

                                resolve(result);
                            };
                        };

                        reader.onerror = reject;

                        reader.readAsDataURL(file);
                    });
                },
            };
        }
    );
};
