//
//  TargetLanguageVC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

var selectedTargetLanguageId = ""

class TargetLanguageVC: ThemeViewController {
    
    // MARK: - Outlets
    
    @IBOutlet var tblViewChooseTranslationLanguage: UITableView!
    @IBOutlet weak var lblNoData: UILabel!
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Class Properties
    
    var arrLanguages: [Language] = []
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Class Functions
    
    private func setupView() {
        self.title = "targetLanguage".localized()
        
        tblViewChooseTranslationLanguage.register(UINib(nibName: "ListCell", bundle: nil), forCellReuseIdentifier: "ListCell")
        tblViewChooseTranslationLanguage.delegate = self
        tblViewChooseTranslationLanguage.dataSource = self
        
        lblNoData.textColor = UIColor.black
        lblNoData.font = Fonts.centuryGothic(ofType: .regular, withSize: 12)
        lblNoData.text = "No Languages Found"
        lblNoData.isHidden = true
        
        //API Calling
        apiGetSupportLanguageCall()
    }

    // -----------------------------------------------------------------------------------------------

    // MARK: - Web Service Functions

    func apiGetSupportLanguageCall() {
        
        if ReachabilityManager.shared.isReachable {
            
            APIManager.shared.request(strURL: String(format:"\(Environment.APIPath)/get_source_language") as String, method: .get, params: nil, completion: { (status, resObj) in
                
                if status == true
                {
                    if (resObj["status"].intValue) == 1
                    {
                        self.arrLanguages = resObj["result"].arrayValue.map{Language(withJSON: $0)}
                        self.tblViewChooseTranslationLanguage.reloadData()
                        
                        if self.arrLanguages.isEmpty {
                            self.lblNoData.isHidden = false
                            self.tblViewChooseTranslationLanguage.isHidden = true
                        }
                    }
                    else
                    {
                        SnackBar.show("\(resObj["message"])")
                    }
                }
                else
                {
                    if resObj["message"].stringValue.count > 0
                    {
                        SnackBar.show("\(resObj["message"])")
                    }
                    else
                    {
                        SnackBar.show("serverTimeout".localized())
                    }
                }
            })
        }
        else
        {
            SnackBar.show("noInternet".localized())
        }
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

// MARK: - UITableView Delegate & DataSource -

extension TargetLanguageVC: UITableViewDelegate, UITableViewDataSource {
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        arrLanguages.count
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let cellSelection = tblViewChooseTranslationLanguage.dequeueReusableCell(withIdentifier: "ListCell") as! ListCell

//        if selectedMenuLanguageId == "16" || selectedMenuLanguageId == "23" || selectedMenuLanguageId == "24" || selectedMenuLanguageId == "27"
//        {
//            cellSelection.lblTitle.textAlignment = NSTextAlignment.right
//        }
//        else
//        {
//            cellSelection.lblTitle.textAlignment = NSTextAlignment.left
//        }
        
        let imageURL = arrLanguages[indexPath.row].image
        cellSelection.imgIcon.isHidden = imageURL.isEmpty
        cellSelection.imgIcon.setImage(withURL: imageURL)
        
        cellSelection.lblTitle.text =  arrLanguages[indexPath.row].name
        
        return cellSelection
    }

    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        selectedTargetLanguageId = arrLanguages[indexPath.row].id
        
        TapticEngine.impact.feedback(.light)
        
//        if selectedTargetLanguageId == "16" || selectedTargetLanguageId == "23" || selectedTargetLanguageId == "24" || selectedTargetLanguageId == "27"
//        {
//            UIView.appearance().semanticContentAttribute = .forceRightToLeft
//            navigationController?.navigationBar.semanticContentAttribute = .forceRightToLeft
//        }
//        else
//        {
//            UIView.appearance().semanticContentAttribute = .forceLeftToRight
//            navigationController?.navigationBar.semanticContentAttribute = .forceLeftToRight
//        }
        
        let objSelectExMode = UIStoryboard.home.instantiateViewController(withClass: SelectExerciseModeVC.self)!
        self.navigationController?.pushViewController(objSelectExMode, animated: true)
    }
}

// -----------------------------------------------------------------------------------------------

