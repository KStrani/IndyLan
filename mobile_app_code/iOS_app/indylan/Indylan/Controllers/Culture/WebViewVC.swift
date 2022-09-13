//
//  WebViewVC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit
import WebKit

class WebViewVC: ThemeViewController {
 
    let indicator = UIActivityIndicatorView()
    
    var strLink = ""
    
    var strTitle = ""
    
    private var webView = WKWebView(frame: .zero)
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        self.title = strTitle.count > 0 ? "" : selectedCategory
        
        removeProfileButton()
    }
    
    override func viewDidLayoutSubviews() {
        super.viewDidLayoutSubviews()
        webView.roundCorners(corners: [.topLeft, .topRight], radius: controllerTopCornerRadius)
    }
    
    override func viewWillAppear(_ animated: Bool) {
        super.viewWillAppear(animated)
        if !strLink.isEmpty, let url = URL(string: strLink) {
            navigationItem.title = !strTitle.isEmpty ? strTitle : selectedCategory
            setupWebView(withUrl: url)
        }
    }
    
    func setupWebView(withUrl url: URL) {
        let webConfiguration = WKWebViewConfiguration()
        webView = WKWebView(frame: .zero, configuration: webConfiguration)
        webView.layer.cornerRadius = controllerTopCornerRadius
        webView.uiDelegate = self
        webView.navigationDelegate = self
        webView.translatesAutoresizingMaskIntoConstraints = false
        webView.clipsToBounds = true
        
        view.addSubview(webView)

        NSLayoutConstraint.activate([
            webView.topAnchor.constraint(equalTo: view.topAnchor, constant: navBarHeight + 8),
            webView.bottomAnchor.constraint(equalTo: view.bottomAnchor),
            webView.leadingAnchor.constraint(equalTo: view.leadingAnchor),
            webView.trailingAnchor.constraint(equalTo: view.trailingAnchor),
        ])
        
        // Indicator Setup
        indicator.translatesAutoresizingMaskIntoConstraints = false
        indicator.activityIndicatorViewStyle = .gray
        indicator.startAnimating()
        
        webView.addSubview(indicator)
        NSLayoutConstraint.activate([
            indicator.centerXAnchor.constraint(equalTo: webView.centerXAnchor),
            indicator.centerYAnchor.constraint(equalTo: webView.centerYAnchor)
        ])
        
        // Loading with URL
        let request = URLRequest(url: url, cachePolicy: .reloadIgnoringLocalAndRemoteCacheData, timeoutInterval: 15)
        webView.load(request)
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
}

// MARK: - WKNavigationDelegate -

extension WebViewVC: WKNavigationDelegate, WKUIDelegate {
    
    func webView(_ webView: WKWebView, didFinish navigation: WKNavigation!) {
        indicator.stopAnimating()
    }
    
    func webView(_ webView: WKWebView, didFail navigation: WKNavigation!, withError error: Error) {
        SnackBar.show("urlLoadFailed".localized())
        indicator.stopAnimating()
        
        let when = DispatchTime.now() + 0.2
        
        DispatchQueue.main.asyncAfter(deadline: when) {
            self.navigationController?.popViewController(animated: true)
        }
    }
}
