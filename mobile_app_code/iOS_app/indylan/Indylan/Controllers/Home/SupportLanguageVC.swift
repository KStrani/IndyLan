//
//  SupportLanguageVC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 20/05/20.
//  Copyright © 2020 Origzo Technologies. All rights reserved.
//

import UIKit

var selectedSupportLanguageId = ""

struct SupportLanguage {
    let id      : String
    let name    : String
    let code    : String
    let image   : String
    
    init(withJSON json: JSON) {
        id      = json["support_lang_id"].stringValue
        name    = json["lang_name"].stringValue
        code    = json["lang_code"].stringValue
        image   = json["image"].stringValue
    }
}

class SupportLanguageVC: ThemeViewController {

    // MARK: - Outlets
    
    @IBOutlet weak var colLanguages: CollectionView!
    
    @IBOutlet weak var lblNoData: UILabel!
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Class Properties
    
    private var arrLanguages: [SupportLanguage] = []
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Memory Management Functions
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    deinit {
        
    }
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Class Functions
    
    private func setupView() {
        self.title = "supportLanguage".localized()
        
        removeBackButton()
        
        setupCollectionView()
        
        lblNoData.textColor = UIColor.black
        lblNoData.font = Fonts.centuryGothic(ofType: .regular, withSize: 12)
        lblNoData.text = "No Languages Found"
        lblNoData.isHidden = true
        
        //API Calling
        apiGetSupportLanguageCall()
    }
    
    private func setupCollectionView() {
        colLanguages.register(UINib(nibName: "SupportLanguageCell", bundle: nil), forCellWithReuseIdentifier: "SupportLanguageCell")
        colLanguages.delegate = self
        colLanguages.dataSource = self
    }
    
    private func updateLanguageToDefault() {
        UserDefaults.standard.set("en", forKey: "selectedLanguageCode")
        UserDefaults.standard.synchronize()
        
        UIView.appearance().semanticContentAttribute = .forceLeftToRight
        navigationController?.navigationBar.semanticContentAttribute = .forceLeftToRight
    }
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Action Functions
    
    
    
    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Web Service Functions
    
    func apiGetSupportLanguageCall() {
        
        if ReachabilityManager.shared.isReachable {
            
            APIManager.shared.request(strURL: String(format:"\(Environment.APIPath)/get_support_language") as String, method: .get, params: nil, completion: { (status, resObj) in
                
                if status == true
                {
                    if (resObj["status"].intValue) == 1
                    {
                        self.arrLanguages = resObj["result"].arrayValue.map{SupportLanguage(withJSON: $0)}
                        self.colLanguages.reloadData()
                        
                        if self.arrLanguages.isEmpty {
                            self.lblNoData.isHidden = false
                            self.colLanguages.isHidden = true
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
    
    override func viewWillAppear(_ animated: Bool) {
        super.viewWillAppear(animated)
        updateLanguageToDefault()
    }
    
    // -----------------------------------------------------------------------------------------------
}

// -----------------------------------------------------------------------------------------------

// MARK: - UICollectionView Delegate & Data Sources

extension SupportLanguageVC: UICollectionViewDelegate, UICollectionViewDataSource, UICollectionViewDelegateFlowLayout {
    
    func collectionView(_ collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        arrLanguages.count
    }
    
    func collectionView(_ collectionView: UICollectionView, cellForItemAt indexPath: IndexPath) -> UICollectionViewCell {
        let cell = collectionView.dequeueReusableCell(withReuseIdentifier: "SupportLanguageCell", for: indexPath) as! SupportLanguageCell
        cell.language = arrLanguages[indexPath.item]
        return cell
    }
    
    func collectionView(_ collectionView: UICollectionView, layout collectionViewLayout: UICollectionViewLayout, sizeForItemAt indexPath: IndexPath) -> CGSize {
        let width = collectionView.frame.width
        let height = width / (isIpad ? 5.7 : 4.7)
        return CGSize(width: width, height: height)
    }

    func collectionView(_ collectionView: UICollectionView, didSelectItemAt indexPath: IndexPath) {
        selectedSupportLanguageId = arrLanguages[indexPath.item].id
        
        TapticEngine.impact.feedback(.light)
        
//        let objSelectLanguage = UIStoryboard.home.instantiateViewController(withClass: MenuLanguageVC.self)!
//        self.navigationController?.pushViewController(objSelectLanguage, animated: true)
        
        UserDefaults.standard.set(arrLanguages[indexPath.row].code, forKey: "selectedLanguageCode")
        UserDefaults.standard.synchronize()
        
        let objSelectTranslationLang = UIStoryboard.home.instantiateViewController(withClass: TargetLanguageVC.self)!
        self.navigationController?.pushViewController(objSelectTranslationLang, animated: true)
    }
    
}

// -----------------------------------------------------------------------------------------------
