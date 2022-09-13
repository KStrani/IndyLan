//
//  TappableImageView.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit
import Photos

// MARK: - Tappable ImageView -

class TappableImageView: UIImageView {
    
    // MARK: - Class Enums
    
    enum SourceType {
        case camera, photoLibrary
    }
    
    // MARK: - Class Variables
    
    var imageChangeBlock: (()->(Void))?
    
    var isRounded: Bool = false
    
    var placeHolderImage: UIImage? = nil
    
    private var imagePicker: UIImagePickerController!
    
    var allowsEditing: Bool = true {
        didSet {
            setupImagePicker()
        }
    }
    
    private var rootVC: UIViewController? {
        return AppDelegate.shared.window?.rootViewController
    }
    
    // MARK: - Custom Methods
    
    private func setupView() {
        //Setup Image View
        clipsToBounds = true
        isUserInteractionEnabled = true
        
        //Add Gesture
        addGestureRecognizer(UITapGestureRecognizer(target: self, action: #selector(showSourceActionSheet)))
        
        //Setup ImagePickerController
        setupImagePicker()
    }
    
    private func setupImagePicker() {
        imagePicker = UIImagePickerController()
        imagePicker.delegate = self
        imagePicker.allowsEditing = allowsEditing
    }
    
    @objc private func showSourceActionSheet() {
        
        let actionSheet = UIAlertController(title: "Select an image", message: nil, preferredStyle: .actionSheet)
        actionSheet.modalPresentationStyle = .popover
        actionSheet.addAction(UIAlertAction(title: "Camera", style: .default, handler: { (_) -> Void in
            self.checkAuthorisationStatusFor(.camera)
        }))
        
        actionSheet.addAction(UIAlertAction(title: "Phone Library", style: .default, handler: { (_) -> Void in
            self.checkAuthorisationStatusFor(.photoLibrary)
        }))
        
        var shouldRemove: Bool = false
        
        if let image = image {
            if let placeHolderImage = self.placeHolderImage {
                if !image.isEqualToImage(image:placeHolderImage) {
                    shouldRemove = true
                }
            } else {
                shouldRemove = true
            }
        }
        
        if shouldRemove {
            actionSheet.addAction(UIAlertAction(title: "Remove", style: .default, handler: { (_) -> Void in
                self.image = self.placeHolderImage
            }))
        }
        
        actionSheet.addAction(UIAlertAction(title: "Cancel", style: .cancel, handler: nil))
        
        DispatchQueue.main.async {
            guard let vc = self.rootVC else { return }
            if UIDevice.current.userInterfaceIdiom == .pad{
                if let popoverPresentationController = actionSheet.popoverPresentationController {
                    popoverPresentationController.sourceView = vc.view
                    popoverPresentationController.sourceRect = CGRect(x: vc.view.bounds.midX, y: vc.view.bounds.height - 80, width: 0, height: 0)
                }
                vc.present(actionSheet, animated: true, completion: nil)
            }else{
                vc.present(actionSheet, animated: true)
            }
        }
    }
    
    //Check permission Status
    
    private func checkAuthorisationStatusFor(_ type: SourceType) {
        let status = PHPhotoLibrary.authorizationStatus()
        switch status {
        case .authorized:
            if type == .camera {
                openCamera()
            } else if type == .photoLibrary {
                openPhotoLibrary()
            }
            break
        case .denied:
            showAlertForSettings(type)
            break
        case .notDetermined:
            PHPhotoLibrary.requestAuthorization({ status in
                if status == PHAuthorizationStatus.authorized {
                    if type == .camera {
                        self.openCamera()
                    } else if type == .photoLibrary {
                        self.openPhotoLibrary()
                    }
                } else {
                    self.showAlertForSettings(type)
                }
            })
            break
        case .restricted:
            showAlertForSettings(type)
            break
        default:
            break
        }
    }
    
    private func showAlertForSettings(_ type: SourceType) {
        
        var alertTitle: String = ""
        
        var alertMessage: String = ""
        
        if type == .camera {
            alertTitle = "Camera Permission"
            
            alertMessage = "\(AppName) does not have access to your camera. To enable access, tap settings and give permission for Camera."
            
        } else if type == .photoLibrary {
            alertTitle = "Library Permission"
            
            alertMessage = "\(AppName) does not have access to your library. To enable access, tap settings and give permission for Photo Library."
        }
        
        DispatchQueue.main.async {
            Alert.showWith(alertTitle, message: alertMessage, positiveTitle: "SETTINGS", negativeTitle: "CANCEL") { isPositive in
                if isPositive {
                    let settingsUrl = URL(string: UIApplicationOpenSettingsURLString)
                    
                    if let url = settingsUrl {
                        if #available(iOS 10.0, *) {
                            UIApplication.shared.open(url, options: [:], completionHandler: nil)
                        } else {
                            UIApplication.shared.openURL(url)
                        }
                    }
                }
            }
        }
    }
    
    //Camera
    
    private func openCamera() {
        if UIImagePickerController.isSourceTypeAvailable(.camera) {
            imagePicker.sourceType = .camera
            DispatchQueue.main.async {
                guard let vc = self.rootVC else { return }
                vc.present(self.imagePicker, animated: true)
            }
        } else {
            DispatchQueue.main.async {
                SnackBar.show("Sorry, this device has no camera")
            }
        }
    }
    
    //Photo Library
    
    private func openPhotoLibrary() {
        if UIImagePickerController.isSourceTypeAvailable(.photoLibrary) {
            imagePicker.sourceType = .photoLibrary
            DispatchQueue.main.async {
                guard let vc = self.rootVC else { return }
                vc.present(self.imagePicker, animated: true)
            }
        }
    }
    
    // MARK: - Life Cycle Methods
    
    override func awakeFromNib() {
        super.awakeFromNib()
        setupView()
    }
    
    override func layoutSubviews() {
        super.layoutSubviews()
        if isRounded {
            layer.cornerRadius = frame.height / 2
        }
    }
    
}

// MARK: - UIImagePickerController Delegates -

extension TappableImageView: UIImagePickerControllerDelegate, UINavigationControllerDelegate {
    
    func imagePickerControllerDidCancel(_: UIImagePickerController) {
        imagePicker.dismiss(animated: true, completion: nil)
    }
    
    func imagePickerController(_: UIImagePickerController, didFinishPickingMediaWithInfo info: [String: Any]) {
        
        if let image = info[allowsEditing ? UIImagePickerControllerEditedImage : UIImagePickerControllerOriginalImage] as? UIImage {
            self.image = image
            
            if let completion = imageChangeBlock {
                completion()
            }
        } else {
            Log.error("Something went wrong in image")
        }
        imagePicker.dismiss(animated: true, completion: nil)
    }
}
