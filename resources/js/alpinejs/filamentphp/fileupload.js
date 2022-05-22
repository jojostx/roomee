export default (Alpine) => {
    Alpine.data('customFileUploadFormComponent', ({
        acceptedFileTypes = ["image/jpg", "image/png", "image/jpeg"],
        isAvatar = false,
        getAltText,
        imageCropAspectRatio,
        imagePreviewHeight = 320,
        maxSize = 5242880,
        hasImage = false,
        minSize,
        minCroppedWidth = 320,
        maxCroppedWidth = 960,
        minCroppedHeight = 320,
        state,
        defaultImageUrl
    }) => {
        return {
            cropper: null,

            aspectRatio: isAvatar ? 1 : imageCropAspectRatio,

            state,

            showCropper: false,

            hasImage,

            hasCropCanvas: false,

            imageElement: "",

            currentInputImage: null,

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

            initCropper() {
                elem = this.$refs.cropCanvas;
                
                if (!this.isValidHTMLImageElement(elem)) {
                    return;
                }
                
                this.cropper = new Cropper(elem, {
                    aspectRatio: this.aspectRatio,
                    viewMode: 3,
                    dragMode: 'move',
                    rotatable: false,
                    scalable: false,
                    toggleDragModeOnDblclick: false,

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
                            cropper.setData({
                                width: Math.max(minCroppedWidth, Math.min(maxCroppedWidth, width)),
                            });
                        }
                    },
                });
            },

            handlefileInputChange() {
                let files = this.$el.files;
                let file;

                if (files && files.length > 0) {
                    file = files[0];

                    if (!this.validFileSize(file.size) || !this.validFileType(file.type)) {
                        return false;
                    }

                    if (URL) {
                        this.updateCropCanvas(URL.createObjectURL(file));
                        
                        this.initCropper();
                    } else if (FileReader) {
                        let reader = new FileReader();
                        
                        reader.onload = (e) => {
                            if (e.loaded) {
                                this.updateCropCanvas(reader.result)
                            }
                        };
                        
                        reader.readAsDataURL(file);
                        
                        this.initCropper();
                    }

                }
            },

            updateCropCanvas(imageSrc = ""){
                if (!imageSrc.trim()) {
                    return;
                }

                this.$refs.cropCanvas.src = imageSrc;

                this.hasCropCanvas = true;
            },

            updatePreview(imageSrc){
                if (typeof imageSrc !== String && !imageSrc.trim()) {
                    return;
                }

                this.$refs.poster.src = imageSrc;
            },

            // implement later
            swap() {
                //update som kind of array
            },

            // implement later
            edit() {
                //show modal by dispatching event 
            },

            cropAndSave() {
                if (this.cropper) {
                    let canvas = this.cropper.getCroppedCanvas({
                        width: imageCropAspectRatio * imagePreviewHeight,
                        height: imagePreviewHeight,
                    })

                    // convert canvas output to blob and upload to Livewire com:
                    canvas.toBlob(function (blob) {

                    }, currentInputImage.type);

                    //if image upload is successful set the preview
                    this.updatePreview(canvas.toDataURL(currentInputImage.type));
                }
            },

            // called when modal is close
            resetCropper(){
                if (this.cropper) {
                    this.cropper.destroy();
                    this.cropper = null;
                }
            },

            getAltText(){
                if (isAvatar) {
                    return getAltText ?? 'avatar image';
                }

                return getAltText ?? 'cover image';
            },

            validFileType(fileType) {
                return acceptedFileTypes.includes(fileType);
            },

            validFileSize(fileSize) {
                return fileSize < maxSize || fileSize > minSize;
            },

            isValidHTMLImageElement(elem){
                if (elem?.src && typeof elem == HTMLImageElement) {
                    return true;
                }

                return false;
            }
        };
    })
}