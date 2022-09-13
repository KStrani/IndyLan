//
//  ThemeViewController.swift
//  Indylan
//
//  Created by Bhavik Thummar on 08/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

class ThemeViewController: UIViewController, FooterLineable {
    
    private let backgroundView = UIView()
    
    private let imgHeaderBackground = UIImageView()
    
    var isProfileController: Bool = false
    
    var navBarHeight: CGFloat {
        UIApplication.shared.statusBarFrame.size.height + (navigationController?.navigationBar.frame.height ?? 0.0)
    }
    
    override var preferredStatusBarStyle: UIStatusBarStyle {
        .lightContent
    }
    
    deinit {
        NotificationCenter.default.removeObserver(self)
    }
    
    private func setupView() {
        view.backgroundColor = Colors.red
        view.clipsToBounds = true
        setupHeaderBackgroundView()
        setupBackgroundView()
        addFooterView()
        addBackButton()
        addProfileButton()
        
        if !isProfileController {
            NotificationCenter.default.addObserver(self, selector: #selector(updateProfileImage(_:)), name: .didUpdateProfilePicture, object: nil)
        }
    }
    
    @objc private func updateProfileImage(_ notification: Notification) {
        if let image = notification.userInfo?["image"] as? UIImage {
            addProfileButton(image: image)
        }
    }
    
    private func setupHeaderBackgroundView() {
        imgHeaderBackground.image = #imageLiteral(resourceName: "header")
        imgHeaderBackground.contentMode = .scaleAspectFill
        imgHeaderBackground.translatesAutoresizingMaskIntoConstraints = false
        view.insertSubview(imgHeaderBackground, at: 0)
        
        NSLayoutConstraint.activate([
            imgHeaderBackground.leadingAnchor.constraint(equalTo: view.leadingAnchor),
            imgHeaderBackground.trailingAnchor.constraint(equalTo: view.trailingAnchor),
            imgHeaderBackground.topAnchor.constraint(equalTo: view.topAnchor),
            imgHeaderBackground.heightAnchor.constraint(equalToConstant: ScreenHeight * 0.25)
        ])
    }
    
    private func setupBackgroundView() {
        backgroundView.backgroundColor = Colors.white
        backgroundView.translatesAutoresizingMaskIntoConstraints = false
        view.insertSubview(backgroundView, at: 1)
        
        NSLayoutConstraint.activate([
            backgroundView.leadingAnchor.constraint(equalTo: view.leadingAnchor),
            backgroundView.trailingAnchor.constraint(equalTo: view.trailingAnchor),
            backgroundView.bottomAnchor.constraint(equalTo: view.bottomAnchor),
            backgroundView.topAnchor.constraint(equalTo: view.topAnchor, constant: navBarHeight + topPadding),
        ])
    }
    
    override func viewDidLoad() {
        super.viewDidLoad()
        setupView()
    }
    
    override func viewDidLayoutSubviews() {
        super.viewDidLayoutSubviews()
        backgroundView.roundCorners(corners: [.topLeft, .topRight], radius: controllerTopCornerRadius)
    }
}
