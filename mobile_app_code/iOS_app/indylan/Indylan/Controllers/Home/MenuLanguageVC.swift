//
//  MenuLanguageVC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

struct Language {
    let id      : String
    let name    : String
    let code    : String
    let image   : String
    
    init(withJSON json: JSON) {
        id      = json["source_language_id"].stringValue
        name    = json["language_name"].stringValue
        code    = json["language_code"].stringValue
        image   = json["image"].stringValue
    }
}

var selectedMenuLanguageId = ""

class MenuLanguageVC: ThemeViewController {

    // MARK: - Outlets
    
    @IBOutlet var tblViewChooseLanguage: TableView!

    @IBOutlet weak var lblNoData: UILabel!
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Class Properties
    
    private var arrLanguages: [Language] = []
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Class Functions
    
    private func setupView() {
        self.title = "chooseMenuLanguage".localized()
        
        lblNoData.textColor = UIColor.black
        lblNoData.font = Fonts.centuryGothic(ofType: .regular, withSize: 12)
        lblNoData.text = "No Languages Found"
        lblNoData.isHidden = true
        
        tblViewChooseLanguage.register(UINib(nibName: "ListCell", bundle: nil), forCellReuseIdentifier: "ListCell")
        tblViewChooseLanguage.delegate = self
        tblViewChooseLanguage.dataSource = self
        
        //API Calling
        apiGetMenuLanguageCall()
    }
 
    private func updateLanguageToDefault() {
        UserDefaults.standard.set("en", forKey: "selectedLanguageCode")
        UserDefaults.standard.synchronize()
        
        UIView.appearance().semanticContentAttribute = .forceLeftToRight
        navigationController?.navigationBar.semanticContentAttribute = .forceLeftToRight
    }
    
    // -----------------------------------------------------------------------------------------------

    // MARK: - Web Service Functions

    func apiGetMenuLanguageCall() {
        
        if ReachabilityManager.shared.isReachable {
            
            APIManager.shared.request(strURL: String(format:"\(Environment.APIPath)/get_source_language") as String, method: .get, params: nil, completion: { (status, resObj) in
                
                if status == true
                {
                    if (resObj["status"].intValue) == 1
                    {
                        self.arrLanguages = resObj["result"].arrayValue.map{Language(withJSON: $0)}
                        self.tblViewChooseLanguage.reloadData()
                        
                        if self.arrLanguages.isEmpty {
                            self.lblNoData.isHidden = false
                            self.tblViewChooseLanguage.isHidden = true
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

extension MenuLanguageVC: UITableViewDelegate, UITableViewDataSource {
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        arrLanguages.count
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let cellSelection = tblViewChooseLanguage.dequeueReusableCell(withIdentifier: "ListCell") as! ListCell
        
        let imageURL = arrLanguages[indexPath.row].image
        cellSelection.imgIcon.isHidden = imageURL.isEmpty
        cellSelection.imgIcon.setImage(withURL: imageURL)
        
        cellSelection.lblTitle.text =  arrLanguages[indexPath.row].name
        
//        if selectedMenuLanguageId == "16" || selectedMenuLanguageId == "23" || selectedMenuLanguageId == "24" || selectedMenuLanguageId == "27"
//        {
//            cellSelection.lblTitle.textAlignment = NSTextAlignment.right
//        }
//        else
//        {
//            cellSelection.lblTitle.textAlignment = NSTextAlignment.left
//        }
        
        return cellSelection
    }

    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        
        UserDefaults.standard.set(arrLanguages[indexPath.row].code, forKey: "selectedLanguageCode")
        UserDefaults.standard.synchronize()
        
        selectedMenuLanguageId = arrLanguages[indexPath.row].id
        
//        if selectedMenuLanguageId == "16" || selectedMenuLanguageId == "23" || selectedMenuLanguageId == "24" || selectedMenuLanguageId == "27"
//        {
//            UIView.appearance().semanticContentAttribute = .forceRightToLeft
//            navigationController?.navigationBar.semanticContentAttribute = .forceRightToLeft
//        }
//        else
//        {
//            UIView.appearance().semanticContentAttribute = .forceLeftToRight
//            navigationController?.navigationBar.semanticContentAttribute = .forceLeftToRight
//        }
        
        TapticEngine.impact.feedback(.light)
        
        let objSelectTranslationLang = UIStoryboard.home.instantiateViewController(withClass: TargetLanguageVC.self)!
        objSelectTranslationLang.arrLanguages = arrLanguages
        self.navigationController?.pushViewController(objSelectTranslationLang, animated: true)
    }
}

// -----------------------------------------------------------------------------------------------

