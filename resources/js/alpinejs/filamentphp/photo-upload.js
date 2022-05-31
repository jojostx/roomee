export default (Alpine) => {
    Alpine.data('customPhotoUploadFormComponent', ({
        acceptedFileTypes = ["image/jpg", "image/png", "image/jpeg"],
        isAvatar = false,
        imageCropAspectRatio,
        imagePreviewHeight = 320,
        maxSize = 5242880,
        minSize = 52428,
        minCroppedWidth = 320,
        maxCroppedWidth = 960,
        minCroppedHeight = 320,
        defaultImageUrl,
        deleteUploadedFileUsing,
        uploadUsing,
        state,
    }) => {
        return {
            state,

            hasImage: defaultImageUrl? true : false,

            croppable: false,

            cropper: null,

            showCropper: false,

            aspectRatio: isAvatar ? 1 : imageCropAspectRatio,

            currentInputImage: null,

            uploadedFileUrlIndex: {},

            shouldUpdateState: true,

            async init() {
                this.$watch('state', async() => {
                    if (!this.shouldUpdateState) {
                        return;
                    }

                    // We don't want to overwrite the files that are already in the input, if they haven't been saved yet.
                    if (Object.values(this.state).filter((file) => file.startsWith('livewire-file:')).length) {
                        return
                    }
                })
            },

            initCropper(id, fileUrl) {
                let elem = this.$refs.cropCanvas ?? document.getElementById('cropCanvas');

                elem.src = elem ? fileUrl : '';

                if (!this.isValidHTMLImageElement(elem)) {
                    return;
                }

                this.cropper = new Cropper(elem, {
                    aspectRatio: this.aspectRatio,
                    viewMode: 3,
                    dragMode: 'move',
                    rotatable: false,
                    scalable: false,
                    toggleDragModeOnDblclick: true,

                    data: {
                        width: minCroppedWidth,
                        height: minCroppedHeight
                    },

                    crop: function(event) {
                        let width = event.detail.width;
                        let height = event.detail.height;

                        if (
                            width < minCroppedWidth ||
                            height < minCroppedHeight ||
                            width > maxCroppedWidth
                        ) {
                            this.cropper.setData({
                                width: Math.max(minCroppedWidth, Math.min(maxCroppedWidth, width)),
                            });
                        }
                    },
                });

                this.croppable = true;

                this.$dispatch('open-modal', {id});
            },

            handleFileInputChange(id) {
                let files = this.$refs.input.files;
                let file;

                if (files && files.length > 0) {
                    file = files[0];

                    if (!this.validFileSize(file.size) || !this.validFileType(file.type)) {
                        return false;
                    }

                    this.currentInputImage = file;

                    if (URL) {                        
                        this.initCropper(id, URL.createObjectURL(file));
                    } else if (FileReader) {
                        let reader = new FileReader();
                        
                        reader.onload = (e) => {
                            if (e.loaded) {
                                this.initCropper(id, reader.result);
                            }
                        };
                        
                        reader.readAsDataURL(file);
                        
                    }

                }
            },

            updatePreview(imageSrc){
                if (typeof imageSrc !== String && !imageSrc.trim()) {
                    return;
                }

                this.$refs.poster.src = imageSrc;
            },
            
            // called when modal is closed
            resetCropper(){
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
                        width: imageCropAspectRatio * imagePreviewHeight,
                        height: imagePreviewHeight,
                    })

                    // convert canvas output to blob and upload to Livewire com:
                    // canvas.toBlob(function (blob) {
                    //     this.upload(blob, (fileKey) => {
                    //         //if image upload is successful set the preview
                    //         this.updatePreview(canvas.toDataURL(this.this.currentInputImage?.type));
                    //         this.resetCropper();
                    //     })
                    // }, this.currentInputImage?.type);

                    this.updatePreview(canvas.toDataURL(this.currentInputImage?.type));

                    this.resetCropper();
                }
            },

            upload(file, load, error, progress) {
                this.shouldUpdateState = false

                let fileKey = ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
                    (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
                )

                uploadUsing(fileKey, file, (fileKey) => {
                    this.shouldUpdateState = true

                    load(fileKey)
                }, error, progress)
            },

            remove: async (source, load) => {
                let fileKey = this.uploadedFileUrlIndex[source] ?? null

                if (! fileKey) {
                    return
                }

                await deleteUploadedFileUsing(fileKey)

                load()
            },

            validFileType(fileType) {
                return acceptedFileTypes.includes(fileType);
            },

            validFileSize(fileSize) {
                return fileSize < maxSize || fileSize > minSize;
            },

            isValidHTMLImageElement(elem){
                if (elem?.src) {
                    return true;
                }

                return false;
            }
        };
    })
}