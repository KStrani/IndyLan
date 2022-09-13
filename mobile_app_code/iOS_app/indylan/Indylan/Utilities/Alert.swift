//
//  Alert.swift
//  Indylan
//
//  Created by Bhavik Thummar on 30/03/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

// MARK: - Alert

final class Alert: UIViewController {

    // MARK: - Class Properties
    
    private lazy var vwBackground: UIView = {
        let view = UIView(frame: .zero)
        view.backgroundColor = Colors.white
        view.clipsToBounds = true
        view.layer.cornerRadius = 12
        view.translatesAutoresizingMaskIntoConstraints = false
        return view
    }()
    
    private lazy var lblTitle: UILabel = {
        let label = UILabel(frame: .zero)
        label.numberOfLines = 0
        label.textAlignment = .center
        label.textColor = Colors.black
        label.font = Fonts.centuryGothic(ofType: .bold, withSize: 18)
        return label
    }()
    
    private lazy var lblDescription: UILabel = {
        let label = UILabel(frame: .zero)
        label.numberOfLines = 0
        label.textAlignment = .center
        label.textColor = Colors.black
        label.font = Fonts.centuryGothic(ofType: .regular, withSize: 15)
        return label
    }()
    
    private lazy var btnPositive: UIButton = {
        let button = getAlertButton()
        button.backgroundColor = Colors.blue
        button.addTarget(self, action: #selector(btnPostiveAction(_:)), for: .touchUpInside)
        button.translatesAutoresizingMaskIntoConstraints = false
        return button
    }()
    
    private lazy var btnNegative: UIButton = {
        let button = getAlertButton()
        button.backgroundColor = Colors.skyBlue
        button.addTarget(self, action: #selector(btnNegativeAction(_:)), for: .touchUpInside)
        return button
    }()
    
    private lazy var labelContainer: UIStackView = {
        let stackView = UIStackView(frame: .zero)
        stackView.axis = .vertical
        stackView.alignment = .fill
        stackView.distribution = .fill
        stackView.spacing = 15
        stackView.addArrangedSubview(lblTitle)
        stackView.addArrangedSubview(lblDescription)
        return stackView
    }()
    
    private lazy var imageView: UIImageView = {
        let imageView = UIImageView(frame: .zero)
        imageView.contentMode = .scaleAspectFit
        imageView.translatesAutoresizingMaskIntoConstraints = false
        return imageView
    }()
    
    private lazy var buttonContainer: UIStackView = {
        let stackView = UIStackView(frame: .zero)
        stackView.axis = .horizontal
        stackView.alignment = .fill
        stackView.spacing = ScreenWidth * 0.04
        stackView.distribution = .fillEqually
        stackView.addArrangedSubview(btnNegative)
        stackView.addArrangedSubview(btnPositive)
        return stackView
    }()
    
    private lazy var baseContainer: UIStackView = {
        let stackView = UIStackView(frame: .zero)
        stackView.axis = .vertical
        stackView.alignment = .center
        stackView.distribution = .fill
        stackView.spacing = 30
        stackView.addArrangedSubview(labelContainer)
        stackView.addArrangedSubview(imageView)
        stackView.addArrangedSubview(buttonContainer)
        stackView.translatesAutoresizingMaskIntoConstraints = false
        return stackView
    }()

    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Class Properties

    private var completion: ((Bool) -> (Void))?
    
    // -----------------------------------------------------------------------------------------------

    // MARK: - Memory Management Functions
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    deinit {
        
    }
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Static Functions

    static func showWith(_ title: String? = nil,
                     message: String? = nil,
                     image: UIImage? = nil,
                     isImageShodow: Bool = false,
                     positiveTitle: String = "OK",
                     negativeTitle: String? = nil,
                     shouldResignOnTouchOutside: Bool = true,
                     completion: ((Bool) -> Void)?) {
        
        let alert = Alert()
        
        alert.showWith(title, message: message?.localized(), image: image, isImageShodow: isImageShodow, positiveTitle: positiveTitle, negativeTitle: negativeTitle, shouldResignOnTouchOutside: shouldResignOnTouchOutside, completion: completion)
    }
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Class Functions
    
    private func setupView() {
        setupBaseContainer()
        
        setupBackgroundView()
        
        view.backgroundColor = Colors.black.withAlphaComponent(0.5)
    }
    
    private func getAlertButton() -> UIButton {
        let button = UIButton(frame: .zero)
        
        button.setTitleColor(Colors.white, for: .normal)
        button.titleLabel?.font = Fonts.centuryGothic(ofType: .bold, withSize: 14)
        button.contentEdgeInsets.top = 15
        button.contentEdgeInsets.bottom = 15
        
        button.layer.cornerRadius = 8
        button.layer.shadowColor = Colors.gray.cgColor
        button.layer.shadowOffset = CGSize(width: 0, height: 4)
        button.layer.shadowRadius = 4
        button.layer.shadowOpacity = 0.8
        
        return button
    }
    
    private func setupBaseContainer() {
        vwBackground.addSubview(baseContainer)
        
        NSLayoutConstraint.activate([
            baseContainer.topAnchor.constraint(equalTo: vwBackground.topAnchor, constant: 25),
            baseContainer.bottomAnchor.constraint(equalTo: vwBackground.bottomAnchor, constant: -25),
            baseContainer.leadingAnchor.constraint(equalTo: vwBackground.leadingAnchor, constant: 25),
            baseContainer.trailingAnchor.constraint(equalTo: vwBackground.trailingAnchor, constant: -25),
            
            labelContainer.leadingAnchor.constraint(equalTo: baseContainer.leadingAnchor),
            labelContainer.trailingAnchor.constraint(equalTo: baseContainer.trailingAnchor),
            
            imageView.leadingAnchor.constraint(equalTo: baseContainer.leadingAnchor),
            imageView.trailingAnchor.constraint(equalTo: baseContainer.trailingAnchor),
            imageView.heightAnchor.constraint(equalTo: imageView.widthAnchor, multiplier: 0.7),
            
            btnPositive.widthAnchor.constraint(equalTo: baseContainer.widthAnchor, multiplier: 0.47)
        ])
    }
    
    private func setupBackgroundView() {
        view.addSubview(vwBackground)
        
        NSLayoutConstraint.activate([
            vwBackground.leadingAnchor.constraint(equalTo: view.leadingAnchor, constant: 25),
            vwBackground.trailingAnchor.constraint(equalTo: view.trailingAnchor, constant: -25),
            vwBackground.centerXAnchor.constraint(equalTo: view.centerXAnchor, constant: 0),
            vwBackground.centerYAnchor.constraint(equalTo: view.centerYAnchor, constant: 0),
        ])
    }
    
    private func setupUIAndData(_ title: String?,
                                message: String? = nil,
                                image: UIImage? = nil,
                                isImageShodow: Bool = false,
                                positiveTitle: String = "OK",
                                negativeTitle: String? = nil) {
        
        if let title = title, !title.isEmpty {
            lblTitle.text = title
        } else { lblTitle.isHidden = true }
        
        if let message = message, !message.isEmpty {
            lblDescription.text = message
        } else { lblDescription.isHidden = true }
        
        labelContainer.isHidden = (lblTitle.isHidden && lblDescription.isHidden)
        
        if let image = image {
            imageView.image = image
            
            if isImageShodow {
                imageView.layer.shadowColor = Colors.gray.withAlphaComponent(0.3).cgColor
                imageView.layer.shadowOffset = CGSize(width: 1, height: 1)
                imageView.layer.shadowRadius = 8
                imageView.layer.shadowOpacity = 0.7
            }
            
        } else { imageView.isHidden = true }
        
        btnPositive.setTitle(positiveTitle, for: .normal)
        
        if let negativeTitle = negativeTitle, !negativeTitle.isEmpty {
            btnNegative.setTitle(negativeTitle, for: .normal)
        } else { btnNegative.isHidden = true }
    }

    func showWith(_ title: String?,
                  message: String? = nil,
                  image: UIImage? = nil,
                  isImageShodow: Bool = false,
                  positiveTitle: String = "OK",
                  negativeTitle: String? = nil,
                  shouldResignOnTouchOutside: Bool = false,
                  completion: ((Bool) -> Void)?) {
        
        setupUIAndData(title, message: message, image: image, isImageShodow: isImageShodow, positiveTitle: positiveTitle, negativeTitle: negativeTitle)
        
        self.completion = completion
        
        if let rootVC = AppDelegate.shared.window?.rootViewController {
            rootVC.addChildViewController(self)
            self.view.frame = UIScreen.main.bounds
            rootVC.view.addSubview(self.view)
            self.didMove(toParentViewController: rootVC)
        }
        
        if shouldResignOnTouchOutside {
            let tempView = UIView(frame: view.bounds)
            tempView.addTapGestureRecognizer {
                self.remove()
            }
            view.insertSubview(tempView, at: 0)
        }

        view.transform = CGAffineTransform(scaleX: 1.3, y: 1.3)
        view.alpha = 0.0

        UIView.animate(withDuration: 0.22, animations: {
            self.view.alpha = 1.0
            self.view.transform = CGAffineTransform(scaleX: 1.0, y: 1.0)
        })
    }
    
    func remove() {
        UIView.animate(withDuration: 0.22, animations: {
            self.view.transform = CGAffineTransform(scaleX: 1.3, y: 1.3)
            self.view.alpha = 0.0
        }, completion: { (finished: Bool) in
            if finished {
                self.willMove(toParentViewController: nil)
                self.view.removeFromSuperview()
                self.removeFromParentViewController()
            }
        })
    }
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Action Functions`
    
    @objc private func btnPostiveAction(_ sender: UIButton) {
        self.remove()
        completion?(true)
    }
    
    @objc private func btnNegativeAction(_ sender: UIButton) {
        self.remove()
        completion?(false)
    }
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Life Cycle Functions
    
    override func viewDidLoad() {
        super.viewDidLoad()
        setupView()
    }

    // -----------------------------------------------------------------------------------------------
    
}

// -----------------------------------------------------------------------------------------------
